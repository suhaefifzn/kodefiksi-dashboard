<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

// ? Service
use App\Services\ArticleService;
use App\Services\CategoryService;

class ArticleController extends Controller
{
    private $articleService;
    private $categoryService;

    public function __construct() {
        $this->articleService = new ArticleService();
        $this->categoryService = new CategoryService();
    }

    public function index(Request $request) {
        $isDraft = $request->query('is_draft');

        if ($isDraft) {
            return view('dashboard.articles.index', [
                'title' => filter_var($isDraft, FILTER_VALIDATE_BOOLEAN) ? 'Artikel Didraft' : 'Artikel Dirilis',
                'data' => [
                    'query' => [
                        'is_draft' => $isDraft
                    ]
                ]
            ]);
        }

        abort(404);
    }

    public function renderTable(Request $request) {
        $isDraft = $request->query('is_draft');

        if ($isDraft or $request->ajax()) {
            $response = $this->articleService->getListArticles($isDraft);
            $decodedResponse = $this->decodeJsonResponse($response);
            $articles = $decodedResponse['data']['articles'];

            return DataTables::of($articles)
                ->addColumn('action', function ($row) {
                    $editButton = '<button class="badge badge-secondary border-0" title="Edit" onclick="editArticle(this)" data-slug="'. $row['slug'] .'"><i class="fa fa-pencil"></i></button>';
                    $showButton = '<button class="badge badge-info border-0" title="Show" onclick="showArticle(this)" data-slug="'. $row['slug'] .'"><i class="fa fa-eye"></i></button>';
                    $deleteButton = '<button class="badge badge-danger border-0" title="Delete" onclick="deleteArticle(this)" data-slug="'. $row['slug'] .'"><i class="fa fa-trash-o"></i></button>';

                    $wrapper = '<div class="d-flex gap-2">' . $editButton . $showButton . $deleteButton . '</div>';

                    return $wrapper;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Query is_draft was not found'
        ], 404);
    }

    public function delete(Request $request) {
        $response = $this->articleService->deleteArticle($request->slug);
        return $response;
    }

    public function detail($slug) {
        $response= $this->articleService->getOneArticle($slug);
        return $response;
    }

    public function create() {
        $getCategories = $this->categoryService->getListCategories();
        $decodedResponseCategories = $this->decodeJsonResponse($getCategories);
        $categories = $decodedResponseCategories['data']['categories'];

        return view('dashboard.articles.create', [
            'title' => 'Tambah Artikel Baru',
            'data' => [
                'categories' => $categories
            ]
        ]);
    }

    public function getDataTableBodyImages(Request $request) {
        if ($request->ajax()) {
            $response = $this->articleService->getBodyImages();
            $decodedResponse = $this->decodeJsonResponse($response);
            $bodyImages = $decodedResponse['data']['article_images'];

            return DataTables::of($bodyImages)
                ->addColumn('action', function ($row) {
                    $showButton = '<button class="badge badge-info border-0" title="Show" onclick="showImage(this)" data-path="'. $row['path'] .'"><i class="fa fa-eye"></i></button>';
                    return $showButton;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function storeBodyImages(Request $request) {
        $image = $request->file('image');
        $response = $this->articleService->addBodyImage($image);
        return $response;
    }

    public function store(Request $request) {
        $payload = [
            'title' => $request->title,
            'slug' => $request->slug,
            'category_id' => $request->category_id,
            'is_draft' => $request->is_draft,
            'excerpt' => $request->excerpt,
            'body' => $request->body
        ];
        $image = $request->file('img_thumbnail');
        $response = $this->articleService->addArticle($payload, $image);

        return $response;
    }

    public function edit($slug) {
        $response = $this->articleService->getOneArticle($slug);
        $decodedResponse = $this->decodeJsonResponse($response);

        $getCategories = $this->categoryService->getListCategories();
        $decodedResponseCategories = $this->decodeJsonResponse($getCategories);
        $categories = $decodedResponseCategories['data']['categories'];

        return view('dashboard.articles.edit', [
            'title' => 'Edit Artikel',
            'data' => [
                'article' => $decodedResponse['data'],
                'categories' => $categories
            ]
        ]);
    }

    public function update(Request $request) {
        $payload = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'is_draft' => $request->is_draft,
            'excerpt' => $request->excerpt,
            'body' => $request->body
        ];
        $image = $request->file('img_thumbnail');
        $response = $this->articleService->editArticle($request->slug, $payload, $image);

        return $response;
    }

    public function generateSlug(Request $request) {
        $response = $this->articleService->generateSlugByTilte($request->title);
        return $response;
    }
}
