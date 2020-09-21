import 'package:json_annotation/json_annotation.dart';

@JsonSerializable()
class Customer {
  int id;
  String customerName;
  String email;
  String password;
  String address;
  String mobile;

  Customer(
      {this.id,
        this.customerName,
        this.email,
        this.password,
        this.address,
        this.mobile});

  factory Customer.fromJson(Map<String, dynamic> json) {
    return Customer(
        id: json['id'],
        customerName: json['customerName'],
        email: json['email'],
        password: json['password'],
        address: json['address'],
        mobile: json['mobile']);
  }
}