CREATE TABLE rooms (
	id INTEGER PRIMARY KEY,
	type VARCHAR,
	price INTEGER,
	available BOOLEAN
);
CREATE TABLE IF NOT EXISTS "bookings" (
	id INTEGER PRIMARY KEY,
	visitor_name VARCHAR,
	arrival_date DATE,
	departure_date DATE,
	room_id INTEGER,
	cost INTEGER,
	transfer_code VARCHAR,
	FOREIGN KEY (room_id) REFERENCES rooms(id)
);
CREATE TABLE addons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR,
    price INTEGER
);
CREATE TABLE sqlite_sequence(name,seq);
CREATE TABLE booking_addons (
    booking_id INTEGER,
    addon_id INTEGER,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (addon_id) REFERENCES addons(id),
    PRIMARY KEY (booking_id, addon_id)
);
