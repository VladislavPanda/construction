<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkerMessage;

class WorkerMessagesScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Мои сообщения';
    public $permission = 'platform.workerMessages';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'messages' => WorkerMessage::where('user_id', Auth::user()->id)->paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Group::make([
                    Button::make('Добавить')
                        ->method('add')
                        ->type(Color::PRIMARY())
                ])->autowidth()
            ]), 

            Layout::table('messages', [
                TD::make('title', 'Тема')
                    ->width('400')
                    ->render(function (WorkerMessage $message) {
                        return Str::limit($message->title);
                        //return view('tableData', ['data' => $project->address]);
                }),

                TD::make('', 'Описание')
                    ->width('400')
                    ->render(function (WorkerMessage $message) {
                        return Str::limit($message->content);
                        //return view('tableData', ['data' => $project->description]);
                }),
            ])
        ];
    }

    public function add(Request $request){
        return redirect()->route('platform.workerMessageAdd');
    }
}
