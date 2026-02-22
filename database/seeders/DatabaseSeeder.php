<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@minegocio.app',
            'password' => bcrypt('password'),
        ]);

        // Expense categories
        $categories = collect([
            'Renta',
            'Servicios',
            'Nomina',
            'Materiales',
            'Transporte',
            'Publicidad',
            'Comida',
            'Otros',
        ])->map(fn (string $name) => ExpenseCategory::create(['name' => $name]));

        // Products
        $products = collect([
            ['name' => 'Producto A', 'sku' => 'SKU-001', 'cost' => 50, 'price' => 120],
            ['name' => 'Producto B', 'sku' => 'SKU-002', 'cost' => 30, 'price' => 75],
            ['name' => 'Producto C', 'sku' => 'SKU-003', 'cost' => 100, 'price' => 250],
            ['name' => 'Producto D', 'sku' => 'SKU-004', 'cost' => 15, 'price' => 40],
            ['name' => 'Producto E', 'sku' => 'SKU-005', 'cost' => 200, 'price' => 450],
            ['name' => 'Servicio Premium', 'sku' => 'SRV-001', 'cost' => 80, 'price' => 300],
            ['name' => 'Accesorio X', 'sku' => 'ACC-001', 'cost' => 10, 'price' => 35],
            ['name' => 'Refaccion Y', 'sku' => 'REF-001', 'cost' => 45, 'price' => 110],
        ])->map(fn (array $data) => Product::create($data));

        // Inventory movements for each product
        foreach ($products as $product) {
            // Initial stock entry
            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'entrada',
                'quantity' => rand(20, 50),
                'reason' => 'Stock inicial',
                'date' => Carbon::now()->subMonths(5),
            ]);

            // Random movements over 5 months
            for ($m = 4; $m >= 0; $m--) {
                $month = Carbon::now()->subMonths($m);
                // 1-3 entries
                for ($i = 0; $i < rand(1, 3); $i++) {
                    InventoryMovement::create([
                        'product_id' => $product->id,
                        'type' => 'entrada',
                        'quantity' => rand(5, 20),
                        'reason' => 'Compra proveedor',
                        'date' => $month->copy()->day(rand(1, $month->daysInMonth)),
                    ]);
                }
                // 1-4 exits
                for ($i = 0; $i < rand(1, 4); $i++) {
                    $maxExit = max(1, (int) ($product->fresh()->stock * 0.3));
                    $qty = rand(1, $maxExit);
                    if ($product->fresh()->stock >= $qty) {
                        InventoryMovement::create([
                            'product_id' => $product->id,
                            'type' => 'salida',
                            'quantity' => $qty,
                            'reason' => 'Venta',
                            'date' => $month->copy()->day(rand(1, $month->daysInMonth)),
                        ]);
                    }
                }
            }
        }

        // Sample sales and expenses for the last 6 months
        for ($m = 5; $m >= 0; $m--) {
            $month = Carbon::now()->subMonths($m);
            $daysInMonth = $month->daysInMonth;

            $salesCount = rand(15, 25);
            for ($i = 0; $i < $salesCount; $i++) {
                Sale::create([
                    'amount' => rand(100, 5000) + (rand(0, 99) / 100),
                    'description' => $this->randomSaleDescription(),
                    'date' => $month->copy()->day(rand(1, $daysInMonth)),
                ]);
            }

            $expensesCount = rand(10, 18);
            for ($i = 0; $i < $expensesCount; $i++) {
                Expense::create([
                    'amount' => rand(50, 3000) + (rand(0, 99) / 100),
                    'expense_category_id' => $categories->random()->id,
                    'description' => $this->randomExpenseDescription(),
                    'date' => $month->copy()->day(rand(1, $daysInMonth)),
                ]);
            }
        }
    }

    private function randomSaleDescription(): string
    {
        $descriptions = [
            'Venta mostrador',
            'Pedido cliente',
            'Venta online',
            'Servicio realizado',
            'Venta mayoreo',
            'Cobro proyecto',
            'Venta producto',
            'Ingreso consultoria',
            'Venta directa',
            'Cobro servicio mensual',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function randomExpenseDescription(): string
    {
        $descriptions = [
            'Pago mensual',
            'Compra de materiales',
            'Pago de servicio',
            'Gasolina',
            'Papeleria',
            'Mantenimiento',
            'Pago proveedor',
            'Insumos oficina',
            'Limpieza',
            'Herramientas',
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
