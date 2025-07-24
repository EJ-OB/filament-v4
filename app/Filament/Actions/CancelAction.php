<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Js;

class CancelAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $url = filament()->getUrl();

        $this->name('back')
            ->label('Cancel')
            ->color('gray')
            ->alpineClickHandler(
                FilamentView::hasSpaMode($url)
                    ? 'document.referrer ? window.history.back() : Livewire.navigate('.Js::from($url).')'
                    : 'document.referrer ? window.history.back() : (window.location.href = '.Js::from($url).')',
            );
    }
}
