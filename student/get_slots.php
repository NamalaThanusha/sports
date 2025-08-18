<?php
// Example: Dynamic slot times (could be from a slots table or hardcoded)
header('Content-Type: application/json');
$slots = [
  '8:00 AM – 10:00 AM',
  '10:00 AM – 12:00 PM',
  '12:00 PM – 2:00 PM',
  '2:00 PM – 4:00 PM',
  '4:00 PM – 6:00 PM'
];
echo json_encode($slots);
