<?php

namespace App\Livewire;

use App\Models\Application;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ApplicationsTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Application::query()
            )

            ->defaultSort('created_at', 'desc')

            // Dynamically generates the URL route for each clicked row
            ->recordUrl(function (Application $record) {
                return route('applications.show', ['id' => $record->APL_ID]);
            })

            // Instructs the browser to open that generated route in a new tab/window
            // ->openRecordUrlInNewTab()

            ->columns([

                TextColumn::make('APL_ID')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('APL_FName')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('APL_LName')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('APL_Programme_Center')
                    ->label('Centre')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('APL_Parent_Name')
                    ->label('Parent')
                    ->searchable(),

                TextColumn::make('APL_Parent_Cellphone')
                    ->label('Phone'),

                TextColumn::make('APL_Status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'info' => 'Pending Review',
                        'success' => 'Accepted',
                        'danger' => 'Declined',
                    ])
                    ->sortable()
                    ->searchable()
            ]);
    }

    public function render(): View
    {
        return view('livewire.pages.applications.filament.applications-table');
    }
}
