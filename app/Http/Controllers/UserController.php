<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected array $breadcrumb = array();

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'User';
        $this->route = $this->routes['web'] . 'admin.user';
        $this->slug = $this->slugs['web'] . 'user';
        $this->view = $this->views['web'] . 'user';
        $this->breadcrumb = $this->breadcrumbs;

        \Inertia\Inertia::share([
            'menu' => $this->menu,
        ]);
    }

    public function index(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = ['label' => 'Users', 'slug' => 'user'];
        return inertia('User/Index', [
            'breadcrumbs' => $breadcrumb
        ]);
    }

    public function getDatatable(Request $request)
    {

        $query = User::query();
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
                } elseif ($filter['matchMode'] == "between") {
                    if (isset($filter['value']) && $filter['value'] !== null) {
                        $date[0] = Carbon::parse($filter['value'][0])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                        $date[1] = Carbon::parse($filter['value'][1])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                        $query->whereBetween("created_at", $date);
                    }
                } elseif ($filter['matchMode'] == "equals") {
                    if (isset($filter['value']) && $filter['value'] !== null) {
                        if ($filter['value'] === "true") {
                            $query->whereNotNull("email_verified_at");
                        } else {
                            $query->whereNull("email_verified_at");
                        }

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
                "email" => $row['email'],
                "email_verified_at" => $row['email_verified_at'],
                "created_at" => $row['created_at'],
                "actions" => [
                    "edit" => route($this->route . ".edit", $row['id']),
                    "show" => route($this->route . ".show", $row['id']),
                    "delete" => $row['id'],
                ],
            );
        }
        return response()->json([
            'users' => $items,
            'total' => $paginate->total(),
        ]);
    }

    public function edit(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = ['label' => 'User', 'slug' => 'user'];
        return inertia('User/Form/Edit', [
            'breadcrumbs' => $breadcrumb
        ]);
    }

    public function show($id): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = ['label' => 'User', 'slug' => 'user', 'url' => '/admin/user'];
        $breadcrumb[] = ['label' => 'Show', 'slug' => 'show'];
        try {
            $user = User::query()->find($id);
            if (!$user) {
                throw new \Exception("User Not Found");
            }
            return inertia('User/Form/Show', [
                'breadcrumbs' => $breadcrumb,
                'user' => $user
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
            $user = User::query()->findOrFail($id);
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
