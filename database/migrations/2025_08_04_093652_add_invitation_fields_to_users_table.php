<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('invitation_code')->nullable()->after('remember_token');
            $table->unsignedBigInteger('invited_by')->nullable()->after('invitation_code');
            $table->boolean('leader_status')->default(false)->after('invited_by');
            $table->decimal('balance', 10, 2)->default(0.00)->after('leader_status');

            // Optional: Foreign key constraint if invited_by refers to another user
            // $table->foreign('invited_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns in reverse order
            $table->dropColumn(['balance', 'leader_status', 'invited_by', 'invitation_code']);
        });
    }
};

