<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();
        });

        DB::table('volunteers')
            ->whereNull('user_id')
            ->orderBy('id')
            ->get(['id', 'email'])
            ->each(function ($volunteer) {
                $userId = DB::table('users')
                    ->where('email', $volunteer->email)
                    ->where('role', 'volunteer')
                    ->value('id');

                if ($userId) {
                    DB::table('volunteers')
                        ->where('id', $volunteer->id)
                        ->update(['user_id' => $userId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
