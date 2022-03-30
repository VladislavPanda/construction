<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use App\Http\Controllers\WorkerMessageController;

class WorkerMessageAddScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Отправить сообщение';
    public $permission = 'platform.workerMessageAdd';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
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
            Layout::columns([
                Layout::rows([
                    Input::make('title')
                        ->title('Тема:')
                        ->required(),

                    TextArea::make('content')
                        ->title('Сообщение')
                        ->rows(6)
                        ->required(),

                    Button::make('Добавить')
                        ->method('submit')
                        ->type(Color::DEFAULT())
                ])
            ])
        ];
    }

    public function submit(Request $request){
        $message = $request->except(['_token']);
        
        $controller = new WorkerMessageController();
        if($controller->store($message) === true) Alert::warning('Сообщение было успешно отправлено');
    }
}
