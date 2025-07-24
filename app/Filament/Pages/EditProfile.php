<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as FilamentEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class EditProfile extends FilamentEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar_url')
                    ->hiddenLabel()
                    ->inlineLabel()
                    ->alignCenter()
                    ->avatar()
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->disk('public')
                    ->directory('avatars'),

                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}
