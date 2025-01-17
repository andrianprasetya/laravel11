<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{

    protected array $breadcrumb = array();

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Sessions';
        $this->route = $this->routes['web'] . 'admin.user.session';
        $this->slug = $this->slugs['web'] . 'session';
        $this->view = $this->views['web'] . 'session';
        $this->breadcrumb = $this->breadcrumbs;

        \Inertia\Inertia::share([
            'menu' => $this->menu,
        ]);
    }

    public function index(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        array_push(
            $breadcrumb,
            ['label' => 'Users', 'slug' => 'user'],
            ['label' => 'Session', 'slug' => 'session']
        );
        return inertia('User/Session/Index', [
            'breadcrumbs' => $breadcrumb
        ]);
    }

    public function getDatatable(Request $request)
    {
        $query = Session::query()->select("*");
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
                "ip_address" => $row['ip_address'],
                "name" => $row['user']['name'],
                "email" => $row['user']['email'],
                "payload" => $row['payload'],
                "last_activity" => $row['last_activity'],
                "actions" => [
                    "show" => route($this->route . ".show", $row['id']),
                ],
            );
        }
        return response()->json([
            'sessions' => $items,
            'total' => $paginate->total(),
        ]);
    }

    public function show($id): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        array_push(
            $breadcrumb,
            ['label' => 'Users', 'slug' => 'user', 'url' => '/admin/user'],
            ['label' => 'Session', 'slug' => 'session', 'url' => '/admin/user/session'],
            ['label' => 'Show', 'slug' => 'show']
        );
        try {
            $session = Session::query()->find($id);
            if (!$session) {
                throw new \Exception("Session Not Found");
            }

            $user = User::query()->find($session->user_id);
            if (!$user) {
                throw new \Exception("User Not Found");
            }

            return inertia('User/Session/Form/Show', [
                'breadcrumbs' => $breadcrumb,
                'session' => $session,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            abort(500);
        }
    }
}
