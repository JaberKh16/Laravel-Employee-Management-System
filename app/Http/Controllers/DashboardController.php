<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Build random chart datasets for all ranges.
     */
    private function generateChartData(): array
    {
        return [
            '7d' => $this->buildRange(
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                base: 4500,
                variance: 2500,
            ),
            '30d' => $this->buildRange(
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                base: 25000,
                variance: 12000,
            ),
            '6m' => $this->buildRange(
                labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                base: 95000,
                variance: 40000,
            ),
            '1y' => $this->buildRange(
                labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                base: 90000,
                variance: 55000,
            ),
        ];
    }

    /**
     * Generate revenue + expenses arrays around a base value.
     */
    private function buildRange(array $labels, int $base, int $variance): array
    {
        $revenue = [];
        $expenses = [];

        foreach ($labels as $_) {
            $rev = $base + random_int(-$variance, $variance);
            // Expenses typically 55%–75% of revenue
            $exp = (int) round($rev * (random_int(55, 75) / 100));

            $revenue[] = max(0, $rev);
            $expenses[] = max(0, $exp);
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'expenses' => $expenses,
        ];
    }

    /**
     * Generate a random collection of "transactions" for the DataTable.
     */
    private function generateTransactions(int $count = 50): array
    {
        $firstNames = [
            'John',
            'Sarah',
            'Michael',
            'Emma',
            'Robert',
            'Lisa',
            'David',
            'Anna',
            'James',
            'Sophia',
            'Daniel',
            'Olivia',
            'William',
            'Mia',
            'Henry',
            'Charlotte',
            'Lucas',
            'Amelia',
            'Ethan',
            'Harper'
        ];
        $lastNames = [
            'Doe',
            'Adams',
            'Kim',
            'Wilson',
            'Chen',
            'Taylor',
            'Brown',
            'Garcia',
            'Miller',
            'Davis',
            'Martinez',
            'Anderson',
            'Thomas',
            'Jackson',
            'White',
            'Harris',
            'Martin',
            'Thompson',
            'Moore',
            'Clark'
        ];
        $statuses = ['active', 'pending', 'inactive'];
        $domains = ['example.com', 'company.io', 'mail.co', 'biz.net', 'work.org'];

        $rows = [];
        for ($i = 0; $i < $count; $i++) {
            $first = $firstNames[array_rand($firstNames)];
            $last = $lastNames[array_rand($lastNames)];
            $name = $first . ' ' . $last;

            $rows[] = [
                'id' => $i + 1,
                'name' => $name,
                'email' => strtolower($first . '.' . $last . '@' . $domains[array_rand($domains)]),
                'initials' => strtoupper(substr($first, 0, 1) . substr($last, 0, 1)),
                'amount' => random_int(50, 9999) + (random_int(0, 99) / 100),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => Carbon::now()
                    ->subDays(random_int(0, 60))
                    ->subHours(random_int(0, 23))
                    ->subMinutes(random_int(0, 59)),
            ];
        }

        // Sort newest first
        usort($rows, fn($a, $b) => $b['created_at'] <=> $a['created_at']);

        return $rows;
    }

    public function index()
    {
        // take all info for dashboard boxes
        $user_count = User::count();
        $state_count = State::count();
        $city_count = City::count();
        $country_count = Country::count();
        $department_count = Department::count();
        $employee_count = Employee::count();
        $chartData = $this->generateChartData();
        $transactions = $this->generateTransactions(50);

        return view('admin.pages.dashboard', compact(
            'user_count',
            'state_count',
            'city_count',
            'country_count',
            'department_count',
            'employee_count',
            'chartData',
            'transactions'
        ));
    }

    public function generateReport()
    {
        $user_count = User::count();
        $state_count = State::count();
        $city_count = City::count();
        $country_count = Country::count();
        $department_count = Department::count();
        $employee_count = Employee::count();

        $pdf = PDF::loadView('admin.pages.Report.report', compact(
            'user_count',
            'state_count',
            'city_count',
            'country_count',
            'department_count',
            'employee_count'
            ))
            ->setPaper('a4')
            ->setOptions([
                'tempDir' => public_path(),
                'chroot' => public_path(),
            ]);
        return $pdf->download('report.pdf');
    }
}
