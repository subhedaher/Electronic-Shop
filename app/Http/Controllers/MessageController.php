<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\FavoriteProduct;
use App\Models\Message;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function index()
    {
        $countCarts = Cart::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $countProductsFavorite = FavoriteProduct::where('user_id', '=', auth('user')->user()->id ?? 0)->count();
        $categories = Category::where('active', '=', true)->get();
        return view('contact', ['countCarts' => $countCarts, 'categories' => $categories, 'countProductsFavorite' => $countProductsFavorite]);
    }

    public function message(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string',
            'subject' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $message = new Message();
            $message->name = $request->input('name');
            $message->subject = $request->input('subject');
            $message->email = $request->input('email');
            $message->phone_number = $request->input('phone_number');
            $message->message = $request->input('message');
            $saved = $message->save();
            return response()->json([
                'status' => $saved,
                'message' => $saved ? 'Send Message' : 'Send Message Failed!'
            ], $saved ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->getMessageBag()->first()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function messages()
    {
        $this->authorize('viewAny');
        $messages = Message::all();
        return view('cms.messages', ['messages' => $messages]);
    }

    public function delete($id)
    {
        $rowsCount = Message::destroy($id);
        return response()->json([
            'status' => $rowsCount,
            'message' => $rowsCount ? "Message Deleted Successfully" : "Message Deleted Failed!"
        ], $rowsCount ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
