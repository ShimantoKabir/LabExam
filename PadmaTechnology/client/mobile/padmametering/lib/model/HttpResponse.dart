import 'Customer.dart';

class HttpResponse {
  final int code;
  final String msg;
  final List<Customer> customers;

  HttpResponse(
      {this.code,
        this.msg,
        this.customers});

  factory HttpResponse.fromJson(Map<String, dynamic> json) {

    var list = json['customers'] as List;
    List<Customer> customers = list.map((i) => Customer.fromJson(i)).toList();

    return HttpResponse(
        code: json['code'],
        msg: json['msg'],
        customers: customers);
  }
}