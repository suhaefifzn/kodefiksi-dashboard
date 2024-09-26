<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

// ? Service
use App\Services\CategoryService;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct() {
        $this->categoryService = new CategoryService();
    }

    public function index() {
        return view('dashboard.categories.index', [
            'title' => 'Kategori'
        ]);
    }

    public function getCategories() {
        $response = $this->categoryService->getListCategories();
        return $response;
    }

    public function getDetailCategory($slug) {
        if (Str::length($slug) > 3) {
            $response = $this->categoryService->getOneCategory($slug);
            return $response;
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Category was not found'
        ], 404);
    }

    public function delete(Request $request) {
        $response = $this->categoryService->deleteCategory($request->slug);
        return $response;
    }

    public function updateCategoryName(Request $request, $slug) {
        if (Str::length($slug) > 3) {
            $response = $this->categoryService->editCategory($slug, $request->name);
            return $response;
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Category was not found'
        ], 404);
    }

    public function store(Request $request) {
        $response = $this->categoryService->addNewCategory($request->name);
        return $response;
    }

    public function renderTable(Request $request) {
        $category = $request->query('category');

        if ($request->ajax()) {
            $response = $this->categoryService->getOneCategory($category);
            $decodedResponse = $this->decodeJsonResponse($response);
            $articles= $decodedResponse['data']['articles']['article_list'];

            $searchTerm = $request->input('search.value');
            if ($searchTerm) {
                $articles = array_filter($articles, function($article) use ($searchTerm) {
                    $titleMatch = stripos($article['title'], $searchTerm) !== false;
                    $statusMatch = stripos($article['is_draft'] ? 'Draft' : 'Dirilis', $searchTerm) !== false;
                    $authorMatch = stripos($article['user']['username'], $searchTerm) !== false;

                    return $titleMatch || $statusMatch || $authorMatch;
                });
            }


            $totalRecords = count($decodedResponse['data']['articles']['article_list']);
            $filteredRecords = count($articles);
            $articles = array_slice($articles, $request->input('start'), $request->input('length'));

            $extraData = [
                'name' => $decodedResponse['data']['name'],
                'slug' => $decodedResponse['data']['slug'],
                'total_articles' => $decodedResponse['data']['articles']['total'],
                'publish_articles_count' => $decodedResponse['data']['articles']['publish_count'],
                'draft_articles_count' => $decodedResponse['data']['articles']['draft_count'],
            ];

            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $articles,
                'extraData' => $extraData,
            ]);
        }
    }
}
