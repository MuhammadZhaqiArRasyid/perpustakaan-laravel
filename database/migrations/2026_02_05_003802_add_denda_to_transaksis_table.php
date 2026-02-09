<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {

            $table->date('tanggal_jatuh_tempo')
                  ->nullable()
                  ->after('tanggal_pinjam');

            $table->integer('denda')
                  ->default(0)
                  ->after('tanggal_kembali');

            $table->enum('status', ['dipinjam', 'dikembalikan', 'hilang'])
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['tanggal_jatuh_tempo', 'denda']);
        });
    }
};

