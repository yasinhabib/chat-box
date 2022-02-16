<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Amqp;
use App\Models\Chat;
use JWTAuth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChatController extends Controller
{
    private $resolverProcess;

    public function publish(Request $request){
        $user = JWTAuth::user();

        $data = array();
        $data['user_id'] = $user->id;
        $data['message'] = $request->get('message');
        $data['target_user_id'] = $request->get('target');

        
        Amqp::publish($request->get('target'), json_encode($data) , [
            'queue' => 'chat-message','exchange_durable' => 'false','exchange'=> 'chat.message','exchange_type' => 'fanout',
        ]);
    }

    public function getAllChat($target_user_id){
        $user = JWTAuth::user();

        $chats = Chat::where(function ($query) use ($user) {
            $query->where('user_id',$user->id)
                  ->orWhere('target_user_id',$user->id);
        })->where(function ($query) use ($target_user_id) {
            $query->where('user_id',$target_user_id)
                  ->orWhere('target_user_id',$target_user_id);
        })->orderBy('created_at','asc')->get();

        foreach($chats as $chat){
            $chat->isSender = $chat->user_id === $user->id ? true : false;
        }        

        return json_encode($chats);
    }

    public function consume(){
        Amqp::consume('chat-message', function ($message, $resolver) {
            $response = json_decode($message->body);
            
            $chat = new Chat;
            $chat->user_id = $response->user_id;
            $chat->message = $response->message;
            $chat->target_user_id = $response->target_user_id;
            $chat->save();
         
            $resolver->acknowledge($message);

            $this->resolverProcess = $resolver;
                 
         },[
            'exchange'=> 'chat.message',
            'exchange_type' => 'fanout',
            'exchange_durable' => 'false',
            'persistent' => true, // required if you want to listen forever
        ]);
    }

    public function stopConsume(){
        $this->resolverProcess->stop();
    }

    public function runConsume(){
        $process = new Process(['php', base_path('artisan'),'chat:consume']);

        // $process->setEnhanceWindowsCompatibility(false);
        $process->setTimeout(0);
        
        $process->disableOutput();

        $process->start();

        $this->resolverProcess = $process;

        $process->wait();
    }
}
