<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Question Additionals')
                        ->columns(1)
                        ->schema([
                            Select::make('subject_id')
                                ->label('Subject')
                                ->relationship('subject', 'code')
                                ->searchable(),
                            Select::make('section')
                                ->label('Section')
                                ->options([
                                    'Part-A' => 'Part A',
                                    'Part-B' => 'Part B',
                                    'Part-C' => 'Part C',
                                ])
                                ->required(),
                            TagsInput::make('year')
                                ->label('Year')
                                ->required(),
                        ]),

                    Section::make('Question')
                        ->columns(1)
                        ->schema([
                            Repeater::make('questions')
                                ->simple(
                                    TextInput::make('question')
                                        ->label('Question')
                                        ->required(),
                                )
                                ->minItems(1)
                                ->defaultItems(1)
                                ->addActionLabel('Add More Questions')
                                ->addActionAlignment('center')
                                ->reorderable(false),
                        ]),
                ]),
                Section::make('Answer')
                    ->schema([
                        MarkdownEditor::make('answer')
                            ->hiddenLabel(true),
                    ])->columns(1),
            ])
            ->columns(1);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        dd($data);
        // Handle the creation of the main question
        $parentQuestion = Question::create([
            'subject_id' => $data['subject_id'],
            'section' => $data['section'],
            'year' => implode(',', $data['year']),
            'question' => $data['questions'][0]['question'], // First question
        ]);

        // Handle the creation of sub-questions
        foreach (array_slice($data['questions'], 1) as $subQuestion) {
            Question::create([
                'subject_id' => $data['subject_id'],
                'section' => $data['section'],
                'year' => implode(',', $data['year']),
                'question' => $subQuestion['question'],
                'parent_id' => $parentQuestion->id, // Link to parent question
            ]);
        }

        $data['id'] = $parentQuestion->id; // Optionally include the parent question ID
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label('Question')
                    ->limit(50),
                TextColumn::make('section')
                    ->label('Section'),
                TextColumn::make('subject.code')
                    ->label('Subject Code'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
