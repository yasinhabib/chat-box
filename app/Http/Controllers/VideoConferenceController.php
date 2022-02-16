<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Amqp;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class VideoConferenceController extends Controller
{
    private $resolverProcess;

    public function consume(){
        Amqp::consume('chat-video', function ($message, $resolver) {
            // $response = json_decode($message->body);
            
            // $chat = new Chat;
            // $chat->user_id = $response->user_id;
            // $chat->message = $response->message;
            // $chat->target_user_id = $response->target_user_id;
            // $chat->save();
         
            $resolver->acknowledge($message);

            $this->resolverProcess = $resolver;
                 
         },[
            'exchange'=> 'chat.video',
            'exchange_type' => 'fanout',
            'exchange_durable' => 'false',
            'persistent' => true, // required if you want to listen forever
        ]);
    }

    public function stopConsume(){
        $this->resolverProcess->stop();
    }

    public function runConsume(){
        $process = new Process(['php', base_path('artisan'),'video:consume']);

        // $process->setEnhanceWindowsCompatibility(false);
        $process->setTimeout(0);
        
        $process->disableOutput();

        $process->start();

        $this->resolverProcess = $process;

        $process->wait();
    }
}
