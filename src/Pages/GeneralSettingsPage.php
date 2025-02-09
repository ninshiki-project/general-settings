<?php

namespace ninshikiProject\GeneralSettings\Pages;

use Exception;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use ninshikiProject\GeneralSettings\Forms\AnalyticsFieldsForm;
use ninshikiProject\GeneralSettings\Forms\ApplicationFieldsForm;
use ninshikiProject\GeneralSettings\Forms\CustomForms;
use ninshikiProject\GeneralSettings\Forms\EmailFieldsForm;
use ninshikiProject\GeneralSettings\Forms\SeoFieldsForm;
use ninshikiProject\GeneralSettings\Forms\SocialNetworkFieldsForm;
use ninshikiProject\GeneralSettings\Helpers\EmailDataHelper;
use ninshikiProject\GeneralSettings\Mail\TestMail;
use ninshikiProject\GeneralSettings\Models\GeneralSetting;
use ninshikiProject\GeneralSettings\Services\MailSettingsService;

class GeneralSettingsPage extends Page
{
    protected static string $view = 'general-settings::filament.pages.general-settings-page';

    public ?array $data = [];

    /**
     * @throws Exception
     */
    public static function getNavigationGroup(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getNavigationGroup();
    }

    public function getSubHeading(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getDescription();
    }

    /**
     * @throws Exception
     */
    public static function getNavigationIcon(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getIcon();
    }

    public static function getNavigationSort(): ?int
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getSort();
    }

    public static function canAccess(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getCanAccess();
    }

    public static function getNavigationLabel(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getNavigationLabel() ?? __('general-settings::default.title');
    }

    /**
     * @throws Exception
     */
    public static function getNavigationParentItem(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getNavigationParentItem();
    }

    public function getTitle(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('general-settings');

        return $plugin->getTitle() ?? __('general-settings::default.title');
    }

    public function mount(): void
    {
        $this->data = GeneralSetting::first()?->toArray() ?: [];

        $this->data['seo_description'] = $this->data['seo_description'] ?? '';
        $this->data['seo_preview'] = $this->data['seo_preview'] ?? '';
        $this->data['theme_color'] = $this->data['theme_color'] ?? '';
        $this->data['seo_metadata'] = $this->data['seo_metadata'] ?? [];
        $this->data = EmailDataHelper::getEmailConfigFromDatabase($this->data);

        if (isset($this->data['site_logo']) && is_string($this->data['site_logo'])) {
            $this->data['site_logo'] = [
                'name' => $this->data['site_logo'],
            ];
        }

        if (isset($this->data['site_favicon']) && is_string($this->data['site_favicon'])) {
            $this->data['site_favicon'] = [
                'name' => $this->data['site_favicon'],
            ];
        }
    }

    public function form(Form $form): Form
    {
        $arrTabs = [];

        if (config('general-settings.show_application_tab', false)) {
            $arrTabs[] = Tabs\Tab::make('Application Tab')
                ->label(__('general-settings::default.application'))
                ->icon('heroicon-o-tv')
                ->schema(ApplicationFieldsForm::get())
                ->columns(3);
        }

        if (config('general-settings.show_analytics_tab', false)) {
            $arrTabs[] = Tabs\Tab::make('Analytics Tab')
                ->label(__('general-settings::default.analytics'))
                ->icon('heroicon-o-globe-alt')
                ->schema(AnalyticsFieldsForm::get());
        }

        if (config('general-settings.show_seo_tab', false)) {
            $arrTabs[] = Tabs\Tab::make('Seo Tab')
                ->label(__('general-settings::default.seo'))
                ->icon('heroicon-o-window')
                ->schema(SeoFieldsForm::get($this->data))
                ->columns(1);
        }

        if (config('general-settings.show_email_tab', false)) {
            $arrTabs[] = Tabs\Tab::make('Email Tab')
                ->label(__('general-settings::default.email'))
                ->icon('heroicon-o-envelope')
                ->schema(EmailFieldsForm::get())
                ->columns(3);
        }

        if (config('general-settings.show_social_networks_tab', false)) {
            $arrTabs[] = Tabs\Tab::make('Social Network Tab')
                ->label(__('general-settings::default.social_networks'))
                ->icon('heroicon-o-heart')
                ->schema(SocialNetworkFieldsForm::get())
                ->columns(2)
                ->statePath('social_network');
        }

        if (config('general-settings.show_custom_tabs')) {
            foreach (config('general-settings.custom_tabs') as $key => $customTab) {
                $arrTabs[] = Tabs\Tab::make($customTab['label'])
                    ->label(__($customTab['label']))
                    ->icon($customTab['icon'])
                    ->schema(CustomForms::get($customTab['fields']))
                    ->columns($customTab['columns'])
                    ->statePath("more_configs.{$key}");
            }
        }

        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs($arrTabs)
                    ->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        $data = $this->form->getState();
        if (config('general-settings.show_email_tab')) {
            $data = EmailDataHelper::setEmailConfigToDatabase($data);
        }
        $data = $this->clearVariables($data);

        GeneralSetting::updateOrCreate([], $data);
        Cache::forget('general_settings');

        $this->successNotification(__('general-settings::default.settings_saved'));
        redirect(request()?->header('Referer'));
    }

    private function clearVariables(array $data): array
    {
        unset(
            $data['seo_preview'],
            $data['seo_description'],
            $data['default_email_provider'],
            $data['smtp_host'],
            $data['smtp_port'],
            $data['smtp_encryption'],
            $data['smtp_timeout'],
            $data['smtp_username'],
            $data['smtp_password'],
            $data['mailgun_domain'],
            $data['mailgun_secret'],
            $data['mailgun_endpoint'],
            $data['postmark_token'],
            $data['amazon_ses_key'],
            $data['amazon_ses_secret'],
            $data['amazon_ses_region'],
            $data['mail_to'],
        );

        return $data;
    }

    private function successNotification(string $title): void
    {
        Notification::make()
            ->title($title)
            ->success()
            ->send();
    }

    public function sendTestMail(MailSettingsService $mailSettingsService): void
    {
        $data = $this->form->getState();
        $email = $data['mail_to'];

        $settings = $mailSettingsService->loadToConfig($data);

        try {
            Mail::mailer($settings['default_email_provider'])
                ->to($email)
                ->send(new TestMail([
                    'subject' => 'This is a test email to verify SMTP settings',
                    'body' => 'This is for testing email using smtp.',
                ]));
        } catch (Exception $e) {
            $this->errorNotification(__('general-settings::default.test_email_error'), $e->getMessage());

            return;
        }

        $this->successNotification(__('general-settings::default.test_email_success') . $email);
    }

    private function errorNotification(string $title, string $body): void
    {
        Log::error('[EMAIL] ' . $body);

        Notification::make()
            ->title($title)
            ->danger()
            ->body($body)
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Save')
                ->label(__('general-settings::default.save'))
                ->color('primary')
                ->submit('Update'),
        ];
    }
}
