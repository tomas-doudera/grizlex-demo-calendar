<?php

namespace App\Filament\Pages;

use App\Settings\CalendarSettings;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ManageCalendarSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament/calendar_settings.title');
    }

    public function getTitle(): string
    {
        return __('filament/calendar_settings.title');
    }

    public function mount(): void
    {
        $settings = app(CalendarSettings::class);

        $this->form->fill($settings->toArray());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make(__('filament/calendar_settings.sections.display'))
                    ->icon(Heroicon::OutlinedTableCells)
                    ->schema([
                        TextInput::make('step_width')
                            ->label(__('filament/calendar_settings.fields.step_width'))
                            ->helperText(__('filament/calendar_settings.helpers.step_width'))
                            ->numeric()
                            ->required()
                            ->minValue(15)
                            ->maxValue(60)
                            ->suffix('px'),
                        TextInput::make('row_height')
                            ->label(__('filament/calendar_settings.fields.row_height'))
                            ->helperText(__('filament/calendar_settings.helpers.row_height'))
                            ->numeric()
                            ->required()
                            ->minValue(40)
                            ->maxValue(200)
                            ->suffix('px'),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    protected function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('calendar-settings-form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment(static::$formActionsAlignment)
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->sticky($this->areFormActionsSticky())
                    ->key('calendar-settings-form-actions'),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament/calendar_settings.actions.save'))
                ->submit('save'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $validator = Validator::make($data, [
            'step_width' => ['required', 'integer', 'min:15', 'max:60'],
            'row_height' => ['required', 'integer', 'min:40', 'max:200'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $settings = app(CalendarSettings::class);
        $settings->fill($validator->validated());
        $settings->save();

        Notification::make()
            ->title(__('filament/calendar_settings.notifications.saved'))
            ->success()
            ->send();
    }
}
