<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\TD;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\TextArea;
use App\Services\AuthHandler;
use Orchid\Screen\Actions\DropDown;
use App\Http\Controllers\ProjectController;
use App\Models\BudgetBid;
use App\Models\User;

class BudgetBidsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    private $projectId;
    public $name = 'Запросы суммы';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $this->projectId = $_GET['project_id'];
        
        return [
            'bids' => BudgetBid::where('status', 'На рассмотрении')->paginate()
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
            Layout::table('bids', [
                TD::make('description', 'Описание')
                    //->width('400')
                    ->render(function (BudgetBid $bid) {
                        return Str::limit($bid->description);
                }),

                TD::make('sum', 'Сумма')
                    //->width('400')
                    ->render(function (BudgetBid $bid) {
                        return Str::limit($bid->sum);
                }),

                TD::make('status', 'Статус')
                    //->width('400')
                    ->render(function (BudgetBid $bid) {
                        return Str::limit($bid->status);
                }),

                TD::make('user', 'Сотрудник')
                    //->width('400')
                    ->render(function (BudgetBid $bid) {
                        $fio = User::select(['first_name', 'surname', 'patronymic'])->where('id', $bid->user_id)->get();
                        $fioString = $fio[0]->surname . ' ' . $fio[0]->first_name . ' ' . $fio[0]->patronymic; 
                        return Str::limit($fioString);
                }),

                TD::make('', '')
                    //->width('200')
                    ->render(function (BudgetBid $bid) {
                        return Group::make([
                            DropDown::make('Управление')
                            ->icon('folder-alt')
                            ->list([
                                Button::make('Одобрить')
                                    ->method('update')
                                    ->icon('like')
                                    ->parameters([
                                        'result' => 'Одобрить',
                                        'bidId' => $bid->id, 
                                        'sum' => $bid->sum,
                                        'project_id' => $this->projectId
                                    ]),

                                Button::make('Отклонить')
                                    ->method('update')
                                    ->icon('minus')
                                    ->parameters([
                                        'result' => 'Отклонить',
                                        'bidId' => $bid->id,
                                        'sum' => null,
                                        'project_id' => $this->projectId
                                    ]),
                            ]),
                        ])->autoWidth();
                    }),
            ]),
        ];
    }

    public function update(Request $request){
        $budgetBid = $request->except(['_token']);

        $controller = new ProjectController();
        $controller->updateBudget($budgetBid);
    }
}
