<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    protected array $breadcrumb = array();

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Role';
        $this->route = $this->routes['web'] . 'admin.user.role';
        $this->slug = $this->slugs['web'] . 'user-role';
        $this->view = $this->views['web'] . 'user';
        $this->breadcrumb = $this->breadcrumbs;

        \Inertia\Inertia::share([
            'menu' => $this->menu,
        ]);
    }

    public function index(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = ['label' => 'Roles', 'slug' => 'role'];
        return inertia('User/Role/Index', [
            'breadcrumbs' => $breadcrumb
        ]);
    }

    public function getDatatable(Request $request)
    {
        $query = Role::query();
        $rows = $request->get('rows', 10); // Default 10 rows
        $page = $request->get('page', 1); // Default page 1

        // Apply global filters
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $field => $filter) {
                if ($filter['matchMode'] == "contains") {
                    if (isset($filter['value']) && $filter['value'] !== null) {
                        $query->whereRaw("{$field}::text ILIKE ?", ['%' . $filter['value'] . '%']);
                    }
                } elseif ($filter['matchMode'] == "equals") {
                    if (isset($filter['value']) && $filter['value'] !== null) {
                        $query->where($field, $filter['value']);
                    }
                }
            }
        }
        // Apply sorting
        if ($request->has('sortField')) {
            $sortField = $request->input('sortField');
            $sortOrder = $request->input('sortOrder') === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortField, $sortOrder);
        }

        // Paginate the results
        $paginate = $query->paginate($rows, ['*'], 'page', $page);

        $items = array();

        foreach ($paginate->items() as $row) {
            $items[] = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "description" => $row['description'],
                "is_active" => $row['is_active'],
                "actions" => [
                    "edit" => route($this->route . ".edit", $row['id']),
                    "show" => route($this->route . ".show", $row['id']),
                    "delete" => $row['id'],
                ],
            );
        }
        return response()->json([
            'roles' => $items,
            'total' => $paginate->total(),
        ]);
    }

    public function edit($id): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = ['label' => 'Role', 'slug' => 'role', 'url' => '/admin/user/role'];
        $breadcrumb[] = ['label' => 'Show', 'slug' => 'show'];
        try {
            $role = Role::query()->find($id);
            if (!$role) {
                throw new \Exception("Role Not Found");
            }
            return inertia('User/Role/Form/Show', [
                'breadcrumbs' => $breadcrumb,
                'role' => $role
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function show($id): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        array_push($breadcrumb,
            ['label' => 'Users', 'slug' => 'user', 'url' => '/admin/user'],
            ['label' => 'Role', 'slug' => 'role', 'url' => '/admin/user/role'],
            ['label' => 'Show', 'slug' => 'show']);
        try {
            $role = Role::query()->find($id);
            if (!$role) {
                throw new \Exception("Role Not Found");
            }
            return inertia('User/Role/Form/Show', [
                'breadcrumbs' => $breadcrumb,
                'role' => $role
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = Role::query()->findOrFail($id);
            $user->delete();

            DB::commit();
            return response()->json([
                'user' => $user,
                'message' => "delete success",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(__CLASS__ . ":" . __FUNCTION__ . ":" . $e->getMessage());
            if ($e instanceof QueryException) {
                return response()->json([
                    'message' => "Invalid Query",
                ], 500);
            } elseif ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => "Data Not Found",
                ], 500);
            } else {
                return response()->json([
                    'message' => "Internal Server Error",
                ], 500);
            }

        }
    }
}
