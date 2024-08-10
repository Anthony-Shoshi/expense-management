<?php

namespace Models;

use DateTime;

class Expense
{
    public int $id;
    public string $title;
    public float $amount;
    public string $category_name;
    public string $username;
    public int $category_id;
    public int $user_id;
    public string $created_at;
}
