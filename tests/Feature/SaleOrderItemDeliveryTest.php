<?php
use App\Models\SaleOrderItem;
use App\Models\StockTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a sale order item delivery', function () {
    // Arrange: Create a sale order item
    $saleOrderItem = SaleOrderItem::factory()->create([
        'quantity' => 10, // Ordered 10 units
    ]);

    // Act: Create a delivery for the sale order item
    $response = $this->postJson(route('deliveries.store', $saleOrderItem), [
        'quantity' => 5, // Deliver 5 units
    ]);

    // Assert: Check if the delivery was recorded successfully
    $response->assertStatus(200);

    // Assert: Check if the delivery record exists
    expect(SaleOrderItemDelivery::count())->toBe(1);

    // Assert: Check if the stock transaction was created
    $stockTransaction = StockTransaction::first();
    expect($stockTransaction)
        ->product_id->toBe($saleOrderItem->product_id)
        ->quantity->toBe(-5) // Stock is deducted by 5 units
        ->transaction_type->toBe('out');
});


it('cannot deliver more than the ordered quantity', function () {
    // Arrange: Create a sale order item with a quantity of 10
    $saleOrderItem = SaleOrderItem::factory()->create([
        'quantity' => 10, // Ordered 10 units
    ]);

    // Act: Try to deliver more than the ordered quantity (12 units)
    $response = $this->postJson(route('deliveries.store', $saleOrderItem), [
        'quantity' => 12,
    ]);

    // Assert: Ensure that the system throws a validation error
    $response->assertStatus(400);
    $response->assertJson(['error' => 'Delivery quantity exceeds ordered quantity']);
});

it('can calculate remaining quantity after partial delivery', function () {
    // Arrange: Create a sale order item with a quantity of 10
    $saleOrderItem = SaleOrderItem::factory()->create([
        'quantity' => 10,
    ]);

    // Deliver 4 units
    SaleOrderItemDelivery::create([
        'sale_order_item_id' => $saleOrderItem->id,
        'quantity' => 4,
        'delivery_date' => now(),
    ]);

    // Act: Call the API to get the delivery status
    $response = $this->getJson(route('deliveries.status', $saleOrderItem));

    // Assert: Check if the remaining quantity is calculated correctly
    $response->assertStatus(200)
        ->assertJson([
            'total_ordered' => 10,
            'total_delivered' => 4,
            'remaining_quantity' => 6, // 10 - 4 = 6 remaining
        ]);
});
