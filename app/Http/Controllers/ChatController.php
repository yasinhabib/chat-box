<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Amqp;
use App\Models\Chat;
use JWTAuth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use ChatMessage;

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

        $chats = Chat::where(function ($query) use($user) {
            $query->where('user_id',$user->id)
                  ->orWhere('target_user_id',$user->id);
        })->where(function ($query) use ($target_user_id) {
            $query->where('user_id',$target_user_id)
                  ->orWhere('target_user_id',$target_user_id);
        })->orderBy('created_at','asc')->get();
        
        $arrListMessage = [];
        foreach($chats as $chat){
            $chat->user_id = intval($chat->user_id);
            $chat->target_user_id = intval($chat->target_user_id);
            $chat->is_sender = $chat->user_id == $user->id ? true : false;

            $getMessage = new ChatMessage\getMessage();
            $getMessage->setId($chat->id);
            $getMessage->setUserId($chat->user_id );
            $getMessage->setMessage($chat->message);
            $getMessage->setTargetUserId($chat->target_user_id);
            if($chat->created_at){
                $getMessage->setCreatedAt(strtotime($chat->created_at));
            }
            if($chat->update_at){
                $getMessage->setUpdateAt(strtotime($chat->update_at));
            }
            $getMessage->setIsSender($chat->is_sender);

            $arrListMessage[] = $getMessage;
        }        

        $listMessage = new ChatMessage\listMessage();
        $listMessage->setMessage($arrListMessage);

        $serializedMessage = $listMessage->serializeToString();
        $byte_array = unpack('C*',$serializedMessage);
        $byte_array = array_values($byte_array);
        return $byte_array;
    }

    public function consume(){
        Amqp::consume('chat-message', function ($message, $resolver) {
            $postMessage = new ChatMessage\postMessage();
            $postMessage->mergeFromString($message->body);
            
            $chat = new Chat;
            $chat->user_id = $postMessage->getUserId();
            $chat->message = $postMessage->getMessage();
            $chat->target_user_id = $postMessage->getTargetUserId();
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

    public function test(){
        $message = [
            'message' => 'test',
            'target_user_id' => 2,
            'user_id' => 1
        ];

        $postMessage = new ChatMessage\postMessage();
        $postMessage->mergeFromJsonString(\json_encode($message));
        // $postMessage->setUserId($message['user_id']);
        // $postMessage->setMessage($message['message']);
        // $postMessage->setTargetUserId($message['target_user_id']);
        $serializedMessage = $postMessage->serializeToString();

        $byte_array = unpack('C*',$serializedMessage);
        $byte_array = array_values($byte_array);
        return $byte_array;

        // $dataBinary = new ChatMessage\postMessage($postMessage);
        // $userId = $dataBinary->getUserId();
        // $message = $dataBinary->getMessage();
        // $target_user_id = $dataBinary->getTargetUserId();

        // echo $userId;
        // echo $message;
        // echo $target_user_id;
    }
}
