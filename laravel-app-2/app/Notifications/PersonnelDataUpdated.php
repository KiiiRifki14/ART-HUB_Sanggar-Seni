<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PersonnelDataUpdated extends Notification
{
    use Queueable;

    public string $updatedBy;
    public ?string $oldSpecialty;
    public ?string $newSpecialty;
    public array  $changedFields;

    /**
     * @param string      $updatedBy     Nama admin yang melakukan perubahan
     * @param array       $changedFields Daftar field yang diubah beserta nilai lama-baru
     * @param string|null $oldSpecialty
     * @param string|null $newSpecialty
     */
    public function __construct(
        string $updatedBy,
        array  $changedFields = [],
        ?string $oldSpecialty = null,
        ?string $newSpecialty = null
    ) {
        $this->updatedBy    = $updatedBy;
        $this->changedFields = $changedFields;
        $this->oldSpecialty = $oldSpecialty;
        $this->newSpecialty = $newSpecialty;
    }

    /**
     * Hanya simpan di database (notifikasi in-app).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke tabel notifications.
     */
    public function toArray(object $notifiable): array
    {
        $specialtyLabel = [
            'penari'       => 'Penari',
            'pemusik'      => 'Pemusik',
            'multi_talent' => 'Multi-Talent / Kru',
        ];

        $detail = '';
        if ($this->oldSpecialty && $this->newSpecialty && $this->oldSpecialty !== $this->newSpecialty) {
            $old = $specialtyLabel[$this->oldSpecialty] ?? $this->oldSpecialty;
            $new = $specialtyLabel[$this->newSpecialty] ?? $this->newSpecialty;
            $detail = " Spesialisasi diubah dari \"{$old}\" menjadi \"{$new}\".";
        }

        return [
            'title'   => 'Data Profil Diperbarui oleh Admin',
            'message' => "Admin {$this->updatedBy} telah memperbarui data profil Anda.{$detail} Jika ada kekeliruan, silakan hubungi admin sanggar.",
            'type'    => 'personnel_data_updated',
            'url'     => route('personnel.profile.edit', [], false),
        ];
    }
}
