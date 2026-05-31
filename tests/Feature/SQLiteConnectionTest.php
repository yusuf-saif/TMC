<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SQLiteConnectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    public function test_sqlite_connection_works(): void
    {
        $this->assertEquals('sqlite', config('database.default'));
        $one = DB::select('select 1 as one');
        $this->assertEquals(1, $one[0]->one);
    }
}
