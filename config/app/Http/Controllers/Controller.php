<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function counterPagination($value, $current, $route, $params = [])
    {
        $from = $current > 4 ? $current - 3 : 1;
        $next = $current > 4 ? $current + 1 : 5;

        // validation dimension 2
        if ($value > 0) {
            $from = $current == $value ? max(1, $from - 1) : $from;
            $next = min($value, ($current == $value ? $next - 1 : $next));
        }

        $data = [];
        for ($i = $from; $i <= $next; $i++) {
            $queryParams = array_replace_recursive($params, ["page" => ["number" => $i]]);
            $data[] = (object)[
                'lable' => $i,
                'is_active' => $i == $current ? true : false,
                'link' => route($route, $queryParams),
            ];
        }

        return $data;
    }

    public function prevPagination($current, $route, $params = [])
    {
        $page = $current > 1 ? $current - 1 : 1;
        $queryParams = array_replace_recursive($params, ["page" => ["number" => $page]]);
        $data['link'] = route($route, $queryParams);
        return (object) $data;
    }

    public function nextPagination($current, $lasPage, $route, $params = [])
    {
        $page = $current < $lasPage ? $current + 1 : $lasPage;
        $queryParams = array_replace_recursive($params, ["page" => ["number" => $page]]);
        $data['link'] = route($route, $queryParams);
        return (object) $data;
    }
}
