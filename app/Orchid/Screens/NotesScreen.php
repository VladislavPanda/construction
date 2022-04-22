<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Textarea;
use App\Http\Controllers\NoteController;

class NotesScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Заметки';

    public $permission = 'platform.notes';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $notes = [];
        $controller = new NoteController();
        $notes = $controller->getItems();

        return [
            'notes' => $notes
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
            Layout::columns([
                Layout::rows([
                    Button::make('Сохранить')
                        ->method('update')
                        ->type(Color::DEFAULT())
                ])
             ]),

            Layout::columns([
                Layout::rows([
                    Matrix::make('notes')
                        ->columns([
                            'Дата заметки',
                            'Текст заметки'
                        ])
                        ->fields([
                            'Дата заметки' => DateTimer::make(),
                            'Текст заметки' => TextArea::make(),
                        ])
                        ->required(),
                ])
            ]),
        ];
    }

    public function update(Request $request){
        $notes = $request->except(['_token']);

        $controller = new NoteController();
        $controller->store($notes);
    }
}
