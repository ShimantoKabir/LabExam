import 'dart:convert';

import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter_phoenix/flutter_phoenix.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart';
import 'package:padmametering/model/Customer.dart';
import 'package:padmametering/model/HttpResponse.dart';

import 'Const.dart';

class CustomerPage extends StatefulWidget {
  final String email;
  final String sessionId;

  CustomerPage({Key key, @required this.email, @required this.sessionId})
      : super(key: key);

  @override
  CustomerPageState createState() =>
      new CustomerPageState(email: email, sessionId: sessionId);
}

class CustomerPageState extends State<CustomerPage> {
  CustomerPageState({Key key, @required this.email, @required this.sessionId});

  String email;
  String sessionId;
  Future<List<Customer>> customers;

  TextEditingController emailTECtl = TextEditingController();
  TextEditingController customerNameTECtl = TextEditingController();
  TextEditingController passwordTECtl = TextEditingController();
  TextEditingController mobileTECtl = TextEditingController();
  TextEditingController addressTECtl = TextEditingController();
  int id;

  @override
  void initState() {
    super.initState();
    customers = getCustomer(serverBaseUrl + '/api/customers');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          iconTheme: IconThemeData(color: Colors.black),
          backgroundColor: Colors.white,
          title: new Text('Customers',
              style:
                  TextStyle(color: Colors.black, fontWeight: FontWeight.bold)),
          centerTitle: true,
        ),
        body: Container(
          child: FutureBuilder<List<Customer>>(
              future: customers,
              builder: (context, snapshot) {
                if (snapshot.hasData) {
                  if (snapshot.data.length > 0) {
                    return ListView.builder(
                      itemBuilder: (context, index) =>
                          buildItem(context, snapshot.data[index]),
                      itemCount: snapshot.data.length,
                    );
                  } else {
                    return Center(
                        child: Text(
                      "[No customer found]",
                      style: TextStyle(
                          fontSize: 20.0,
                          color: Colors.red,
                          fontFamily: 'Armata',
                          fontWeight: FontWeight.bold),
                    ));
                  }
                } else {
                  return Center(
                    child: CircularProgressIndicator(
                      valueColor: AlwaysStoppedAnimation<Color>(Colors.red),
                    ),
                  );
                }
              }),
        ),
        bottomNavigationBar: Container(
          color: Colors.white,
          height: 50.0,
          alignment: Alignment.center,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: <Widget>[
              IconButton(
                  icon: Icon(Icons.add),
                  onPressed: () {
                    customerNameTECtl.text = "";
                    emailTECtl.text = "";
                    passwordTECtl.text = "";
                    mobileTECtl.text = "";
                    addressTECtl.text = "";
                    id = 0;

                    showForm(1);
                  }),
              IconButton(
                  icon: Icon(Icons.filter_list),
                  onPressed: () {
                    customerNameTECtl.text = "";
                    emailTECtl.text = "";
                    passwordTECtl.text = "";
                    mobileTECtl.text = "";
                    addressTECtl.text = "";
                    id = 0;

                    showForm(3);
                  }),
              IconButton(
                  icon: Icon(Icons.refresh),
                  onPressed: () {
                    setState(() {
                      customers = getCustomer(serverBaseUrl + '/api/customers');
                    });
                  })
            ],
          ),
        ));
  }

  void showForm(int type) {
    showDialog(
      context: context,
      builder: (context) => new AlertDialog(
        title: Text(
            (type == 1)
                ? "Create Customer"
                : (type == 2) ? "Update Customer" : "Filter Customer",
            style: TextStyle(
              fontSize: 25.0,
              fontWeight: FontWeight.bold,
            )),
        content: SingleChildScrollView(
          child: Wrap(children: <Widget>[
            Visibility(
              visible: type != 3,
              child: Text('Name'),
            ),
            Visibility(
              visible: type != 3,
              child: TextField(
                controller: customerNameTECtl,
              ),
            ),
            Padding(
                padding: EdgeInsets.fromLTRB(0, 10, 0, 0),
                child: Text('Email')),
            TextField(
              controller: emailTECtl
            ),
            Padding(
                padding: EdgeInsets.fromLTRB(0, 10, 0, 0),
                child: Text('Mobile')),
            TextField(
              controller: mobileTECtl,
            ),
            Visibility(
              visible: type != 3,
              child: Padding(
                  padding: EdgeInsets.fromLTRB(0, 10, 0, 0),
                  child: Text('Address')),
            ),
            Visibility(
              visible: type != 3,
              child: TextField(
                controller: addressTECtl,
              ),
            ),
            Visibility(
              visible: type != 3,
              child: Padding(
                  padding: EdgeInsets.fromLTRB(0, 10, 0, 0),
                  child: Text('Password')),
            ),
            Visibility(
              visible: type != 3,
              child: TextField(
                controller: passwordTECtl,
              ),
            )
          ]),
        ),
        actions: <Widget>[
          FlatButton(
              child: Text('Close'),
              onPressed: () {
                Navigator.of(context, rootNavigator: true).pop('dialog');
              }),
          FlatButton(
              child: (type == 1)
                  ? Text('Save')
                  : (type == 2) ? Text('Update') : Text('Filter'),
              onPressed: () {
                (type == 3) ? onFilter(context) : checkInput(context, type);
              })
        ],
      ),
    );
  }

  showAlertDialog(BuildContext context, String msg) {
    AlertDialog alert = AlertDialog(
      content: ListTile(
        leading: CircularProgressIndicator(),
        title: Text("Loading"),
        subtitle: Text(msg),
      ),
    );
    showDialog(
      barrierDismissible: false,
      context: context,
      builder: (BuildContext context) {
        return alert;
      },
    );
  }

  void checkInput(BuildContext context, int type) {
    if (customerNameTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Name required.");
    } else if (passwordTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Password required.");
    } else if (emailTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Email required.");
    } else if (addressTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Address required.");
    } else if (mobileTECtl.text.toString().isEmpty) {
      Fluttertoast.showToast(msg: "Mobile required.");
    } else {
      showAlertDialog(
          context,
          (type == 1)
              ? "Please wait, Customer creation processing..."
              : "Please customer update processing....!");
      (type == 1) ? onCreate() : onUpdate();
    }
  }

  Future<List<Customer>> getCustomer(String url) async {
    List<Customer> customers = [];
    Map<String, String> headers = {"Content-type": "application/json"};

    Response response = await get(url, headers: headers);

    if (response.statusCode == 200) {
      print(response.body);
      var body = json.decode(response.body);

      if (body['code'] == 200) {
        customers = HttpResponse.fromJson(json.decode(response.body)).customers;
      } else {
        Fluttertoast.showToast(msg: body['msg']);
      }
    } else {
      Fluttertoast.showToast(msg: "Something went wrong!");
    }

    return customers;
  }

  buildItem(BuildContext context, customer) {
    return Card(
      child: ListTile(
        title: Text("Name: ${customer.customerName}"),
        subtitle: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: <Widget>[
            Text("Email: ${customer.email}"),
            Text("Mobile: ${customer.mobile}"),
            Text("Address: ${customer.address}"),
          ],
        ),
        trailing: InkWell(
          child: Icon(Icons.delete),
          onTap: () {
            showAlertDialog(context, "Please wait delete processing...");
            onDelete(customer.id);
          },
        ),
        isThreeLine: true,
        onTap: () {
          customerNameTECtl.text = customer.customerName;
          emailTECtl.text = customer.email;
          addressTECtl.text = customer.address;
          mobileTECtl.text = customer.mobile;
          passwordTECtl.text = customer.password;
          id = customer.id;

          showForm(2);
        },
      ),
    );
  }

  Future<void> onCreate() async {
    String url = serverBaseUrl + '/api/customers';
    Map<String, String> headers = {"Content-type": "application/json"};
    var request = {
      'user': {'email': email, 'sessionId': sessionId},
      'customer': {
        'customerName': customerNameTECtl.text,
        'email': emailTECtl.text,
        'password': passwordTECtl.text,
        'mobile': mobileTECtl.text,
        'address': addressTECtl.text,
      }
    };
    print(url);
    Response response =
        await post(url, headers: headers, body: json.encode(request));

    Navigator.of(context, rootNavigator: true).pop('dialog');

    if (response.statusCode == 200) {
      print(response.body);
      var body = json.decode(response.body);

      if (body['code'] == 200) {
        customerNameTECtl.text = "";
        emailTECtl.text = "";
        passwordTECtl.text = "";
        mobileTECtl.text = "";
        addressTECtl.text = "";
        setState(() {
          customers = getCustomer(serverBaseUrl + '/api/customers');
        });
      }

      Fluttertoast.showToast(msg: body['msg']);
    } else {
      Fluttertoast.showToast(msg: "Something went wrong!");
    }
  }

  onUpdate() async {
    String url = serverBaseUrl + '/api/customers/$id';
    Map<String, String> headers = {"Content-type": "application/json"};
    var request = {
      'user': {'email': email, 'sessionId': sessionId},
      'customer': {
        'customerName': customerNameTECtl.text,
        'email': emailTECtl.text,
        'password': passwordTECtl.text,
        'mobile': mobileTECtl.text,
        'address': addressTECtl.text,
      }
    };
    print(url);
    Response response =
        await put(url, headers: headers, body: json.encode(request));

    Navigator.of(context, rootNavigator: true).pop('dialog');

    if (response.statusCode == 200) {
      print(response.body);
      var body = json.decode(response.body);

      if (body['code'] == 200) {
        customerNameTECtl.text = "";
        emailTECtl.text = "";
        passwordTECtl.text = "";
        mobileTECtl.text = "";
        addressTECtl.text = "";
        setState(() {
          customers = getCustomer(serverBaseUrl + '/api/customers');
        });
      }

      Fluttertoast.showToast(msg: body['msg']);
    } else {
      Fluttertoast.showToast(msg: "Something went wrong!");
    }
  }

  Future<void> onDelete(int id) async {
    String url = serverBaseUrl + '/api/customers/$id';
    Map<String, String> headers = {"Content-type": "application/json"};

    print(url);
    Response response = await delete(url, headers: headers);

    Navigator.of(context, rootNavigator: true).pop('dialog');

    if (response.statusCode == 200) {
      print(response.body);
      var body = json.decode(response.body);

      if (body['code'] == 200) {
        setState(() {
          customers = getCustomer(serverBaseUrl + '/api/customers');
        });
      }

      Fluttertoast.showToast(msg: body['msg']);
    } else {
      Fluttertoast.showToast(msg: "Something went wrong!");
    }
  }

  onFilter(BuildContext context) {
    setState(() {
      customers = getCustomer(serverBaseUrl +
              '/api/customers/filter?email=${emailTECtl.text}&mobile=${mobileTECtl.text}')
          .whenComplete(() {
        Navigator.of(context, rootNavigator: true).pop('dialog');
      });
    });
  }
}
