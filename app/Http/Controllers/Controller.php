<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function res_suu($msg = '',$data = null){
        return response()->json([
            'result' => true,
            'message' => $msg,
            'data' => $data
        ]);
    }

    public function res_paginatte($page, $msg = '', $data)
    {
        return response()->json([
            'result' => true,
            'message' => $msg,
            'data' => $data,
            'paginate' => [
                'total' => $page->total(),
                'total_page' => $page->lastPage(),
                'current' => $page->currentPage(),
                'has_morepages' => $page->hasMorePages(),
                'has_page' => $page->hasPages()
            ]
        ]);
    }



}
