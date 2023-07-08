<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SaveBookRequest;
use App\Http\Controllers\Api\Controller;

class BookController extends Controller
{
    public function index()
    {
        try {
            $books = Book::all();
            
            return response()->json(['success' => true, 'data' => $books]); 
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Error de servidor',
                'info' => [
                    'info_error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json(['success' => false, 'message' => 'El libro no existe.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['success' => true, 'data' => $book]);
    }

    public function store(SaveBookRequest $request)
    {
        $book = Book::create($request->all());

        return response()->json(['success' => true, 'message' => 'Libro creado con éxito', 'data' => $book]);
    }

    public function update(SaveBookRequest $request, $id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json(['success' => false, 'message' => 'El libro no existe.'], Response::HTTP_NOT_FOUND);
        }

        $book->update($request->all());

        return response()->json(['success' => true, 'message' => 'Libro actualizado con éxito.', 'data' => $book]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json(['success' => false, 'message' => 'El libro no existe.'], Response::HTTP_NOT_FOUND);
        }

        $book->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
