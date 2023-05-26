// Assume that $bookingData contains the data submitted by the user
// including the item ID, start time, and end time.

$db = \Config\Database::connect();
$builder = $db->table('bookings');

$db->transStart();

// Use a database transaction to ensure atomicity
try {
    // Use a database lock to prevent concurrent access to the booking data
    $builder->where('item_id', $bookingData['item_id'])
            ->where('start_time <=', $bookingData['end_time'])
            ->where('end_time >=', $bookingData['start_time'])
            ->setLockForUpdate(true)
            ->get();

    // Check if any rows are returned, indicating that the item is already booked
    if ($builder->countAllResults() > 0) {
        $db->transRollback();
        // Handle the error and return a response to the user
        return "Sorry, the item is already booked at that time.";
    }

    // Insert the new booking into the database
    $builder->insert($bookingData);

    $db->transCommit();
    // Handle the success and return a response to the user
    return "Booking successful.";
} catch (Exception $e) {
    $db->transRollback();
    // Handle the error and return a response to the user
    return "An error occurred: " . $e->getMessage();
}





2//




$validation = \Config\Services::validation();
$validation->setRules([
    'email' => [
        'label' => 'Email',
        'rules' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']'
    ]
]);


if (!$this->validate($validation)) {
    $data['validation'] = $this->validator;
    // show the edit form with validation errors
} else {
    // update the user's details in the database
}
