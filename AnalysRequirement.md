AnalysRequirement

# 1. Requirements
### [User]
- User

##### Features:
- Signup, Login, Logout
- Confirm account, Reset password
- View user profile, Edit profile
- Search co-working space by:
  + Name
  + Address (city, street)
  + Type (private office, working desk, meeting space)
  + Price (per month, per day, per hour)
  + Available time
- Get user's location to show recommendations
- Book a co-working space
- <code style="color : red">Make payment after confirmation</code> 
- <code style="color : red">Cancel before making payment</code>  
- View his booking history
- View his spaces booking state
- Create venue
  + Add amenities (wifi, lockers, conditional, ...)
  + Add spaces (name, type, capacity, description, price, hour open/close)
      ► Type includes: private office, meeting room, desk
      ► Price: per month/day/hour
  + Add managers for space
- View, edit, delete his space
- View, edit, delete his venue
- Chat with other users
- <code style="color : red">Show Google map for venues</code>  
- <code style="color : red">Click on map to see more info</code>  
-  <code style="color : red">Change language: English & Vietnamese</code> 
- <code style="color : red">Search on map (when moving)</code> 


### [Moderator]: Kiểm duyệt viên

##### Roles
- Moderator

##### Features
- Manage users (active, deactive user, mark user as verified)
- Manage venues (view, block, approve)
- View all booking

### [Admin] 

##### Roles
- Admin

##### Features
- Manage users (active, deactive user, mark user as verified)
- Manage venues (view, block, approve)
- View all booking
- View statistics, payment history
- Change user role

### [System]

##### Features
- Send email to user when user sign up
- Send email to user to reset password
- <code style="color : red">Send email to user when booking status changed</code> 
- <code style="color : red">Notification system</code>  
- <code style="color : red">SMS system</code> 

# 2. Database Design
### Relation Diagram
![alt text](<Co-working Space System.png>)

### Description
table `users` {
  id int [pk]
  username varchar
  phone_number varchar
  email varchar
  password varchar 
  status ENUM(active, deactive, verified)
  role_id int
}

// 3 roles: User, Monderator, Admin
table `roles` {
  id int [pk]
  name varchar
  description text
}

table `provinces` {
  id int [pk]
  code varchar
  name varchar
  name_en varchar
  full_name varchar 
  full_name_en varchar 
}

table `wards` {
  id int [pk]
  code varchar
  province_id int
  name varchar 
  name_en varchar
  full_name varchar
  full_name_en varchar 
}

table `venues` {
  id	int [PK]
  owner_id	int 
  name	varchar	
  address	text
  ward_id	int	
  lat	decimal	 // Latitude
  lng	decimal	// Longitude
  description	text	
  status	ENUM(pending, approved, blocked)
}

table `spaces` {
  id int [pk]
  venue_id int 
  name	varchar
  space_type_id	int
  capacity	int	// Max people
  price_type_id	int
  price	decimal(10,2)
  description	text
  status	enum(available, unavailable) // available: có thể sử dụng , unavailable: bảo trì/không sử dụng nữa ...
}

table `space_types` {
  id int [pk]
  name varchar // private_office, meeting_room
  description text
}

table `price_types` {
  id int [pk]
  code varchar // month, day, hour
  name varchar // Month, Day, Hour
  name_en varchar // Tháng, Ngày, Giờ
}

// Tiện ích: wifi, lockers, conditional,...
table `amenities` {
  id int [pk]
  code varchar // Mã thiết bị 
  name varchar
  description text
  venue_id int
}

table `space_amenities` {
  space_id int
  amenity_id int
}

table `venue_managers` {
  user_id int
  venue_id int
}

// Giờ mở cửa cố định
// Đơn giản: giờ mở / đóng cửa cố định
// T2-T6: 8:00 - 16:00
// T7: 8:00 - 15:00
table `space_operating_hours` {
  id int [pk]
  space_id int
  day_of_week ENUM(mon, tue, wed, thu, fri, sat, sun)
  open_time   time
  close_time  time
  is_closed   boolean // DEFAULT false
}

// Sử dụng trong 1 số trường hợp space nghỉ vào ngày mở cửa mặc định,...
// Exeption: 2/9 nghỉ 
table `space_special_hours` {
  id int [pk]
  space_id int
  date date
  open_time   time
  close_time  time
  is_closed   boolean // DEFAULT true
  reason text
}

// Luồng
// Status của booking 
// 1. Hiện tại: Chưa booking
// 2. User Booking: "Chờ xác nhận" => pending 
// 3. Admin: accept -> "Đã xác nhận - Chờ thanh toán": confirmed-unpaid
// 4. User: Thanh toán => "Đã thanh toán - Chờ xác nhận": paid-pending
//                     "Thanh toán một phần - Chờ xác nhận": partial-pending 
// 5. Admin: Xác nhận => "Đã xác nhận - Đặt thành công": accepted: User có thể đến checkin/checkout
// 6. User: Checkout trả phòng => "Đã hoàn thành": done 
// chưa thanh toán, đã trả một phần, đã thanh toán
table `bookings` {
  id int [pk] 
  user_id int 
  space_id int
  start_time datetime // thời gian đặt trước
  end_time datetime   // thời gian đặt trước
  check_in datetime   // thời gian thực tế vào
  check_out datetime  // thời gian thực tế ra
  status enum('pending', 'confirmed-unpaid', 'paid-pending', 'partial-pending ', 'accepted', 'done') //
  status_payment enum('unpaid', 'partial', 'paid') // trạng thái thanh toán: chưa thanh toán, đã trả một phần, đã thanh toán
  total_price decimal(10,2)
}

table `payments` {
  id int [pk] 
  booking_id int 
  method enum('momo', 'vnpay')
  amount decimal(10,2)
  status enum('success', 'failed', 'refunded')
  paid_at timestamp
  order_id varchar
  trans_id varchar
}

### Migrate CLI
#### Craete migration files
> php artisan make:migration create_roles_table
php artisan make:migration create_users_table
php artisan make:migration create_provinces_table
php artisan make:migration create_wards_table
php artisan make:migration create_venues_table
php artisan make:migration create_space_types_table
php artisan make:migration create_price_types_table
php artisan make:migration create_spaces_table
php artisan make:migration create_amenities_table
php artisan make:migration create_space_amenities_table
php artisan make:migration create_venue_managers_table
php artisan make:migration create_space_operating_hours_table
php artisan make:migration create_space_special_hours_table
php artisan make:migration create_bookings_table
php artisan make:migration create_payments_table



