<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{

    protected array $breadcrumb = array();

    public function __construct()
    {
        parent::__construct();
        $this->menu = 'Log';
        $this->route = $this->routes['web'] . 'admin.users.log';
        $this->slug = $this->slugs['web'] . 'log';
        $this->view = $this->views['web'] . 'log';
        $this->breadcrumb = $this->breadcrumbs;
    }

    public function index(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        $breadcrumb = $this->breadcrumb;
        array_push($breadcrumb,['label' => 'Users', 'slug' => 'user'],['label' => 'Log', 'slug' => 'log']);
        return inertia('User/LogSession', [
            'breadcrumbs' => $breadcrumb
        ]);
    }

    public function getDatatable(Request $request)
    {
        $query = Session::with('user');
        // Apply global filters
        if ($request->has('filters')) {
            $filters = $request->input('filters');
            foreach ($filters as $field => $filter) {
                if (isset($filter['value']) && $filter['value'] !== null) {
                    $query->where($field, 'like', '%' . $filter['value'] . '%');
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
        $sessions = $query->paginate($request->input('rows', 10));
        return response()->json([
            'sessions' => $sessions->items(),
            'total' => $sessions->total(),
        ]);
    }
}
