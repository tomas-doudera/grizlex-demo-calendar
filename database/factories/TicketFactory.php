<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(TicketStatus::cases());
        $isResolved = in_array($status, [TicketStatus::Resolved, TicketStatus::Closed]);

        return [
            'customer_id' => Customer::factory(),
            'project_id' => fake()->boolean(60) ? Project::factory() : null,
            'assigned_to' => fake()->boolean(70) ? User::factory() : null,
            'ticket_number' => 'TKT-'.fake()->unique()->numerify('####'),
            'subject' => fake()->sentence(fake()->numberBetween(4, 8)),
            'description' => fake()->paragraphs(2, true),
            'status' => $status,
            'priority' => fake()->randomElement(TicketPriority::cases()),
            'category' => fake()->randomElement(['Bug', 'Feature Request', 'Support', 'Question', 'Billing']),
            'is_resolved' => $isResolved,
            'resolved_at' => $isResolved ? fake()->dateTimeBetween('-1 month') : null,
            'first_response_at' => fake()->boolean(80) ? fake()->dateTimeBetween('-3 months', '-1 day') : null,
        ];
    }
}
