<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supplier_id')
                    ->translateLabel()
                    ->relationship('supplier','name')
                    ->required(),
                Forms\Components\TextInput::make('expense_ref')
                    ->translateLabel()
                    ->maxLength(32)
                    ->required(),
                Forms\Components\DatePicker::make('expense_date')
                    ->translateLabel()
                    ->displayFormat('d/m/Y')
                    ->required(),
                Forms\Components\TextInput::make('expense_amt')
                    ->label('Total amount')
                    ->translateLabel()
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expense_ref')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expense_date')
                    ->translateLabel()
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('expense_amt')
                    ->translateLabel()
                    ->money('eur')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->color('secondary')
                    ->form([
                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier','name')
                            ->required(),
                        Forms\Components\TextInput::make('expense_ref')
                            ->maxLength(32)
                            ->required(),
                        Forms\Components\DatePicker::make('expense_date')
                            ->displayFormat('d/m/Y')
                            ->required(),
                        Forms\Components\TextInput::make('expense_amt')
                            ->numeric()
                            ->required(),
                    ])
                    ->beforeFormFilled(function(Model $record): void {
                        $record->expense_ref = null;
                        $record->expense_amt = $record->expense_amt / 100;
                    })
                    ->beforeReplicaSaved(function (Model $replica, array $data): void {
                        $data['expense_amt'] = $data['expense_amt'] * 100;
                        $replica->fill($data);
                    })

                ,
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
