// This is a basic Flutter widget test.
//
// To perform an interaction with a widget in your test, use the WidgetTester
// utility in the flutter_test package. For example, you can send tap and scroll
// gestures. You can also use WidgetTester to find child widgets in the widget
// tree, read text, and verify that the values of widget properties are correct.

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:mobile/main.dart';

void main() {
  testWidgets('App smoke test', (WidgetTester tester) async {
    // Setup SharedPreferences mock
    SharedPreferences.setMockInitialValues({});

    final prefs = await SharedPreferences.getInstance();
    final config = {
      'api_url': 'http://127.0.0.1:8000',
      'app_name': 'Test App',
      'app_version': '1.0.0',
      'debug': true,
    };

    // Build our app and trigger a frame.
    await tester.pumpWidget(MyApp(prefs: prefs, config: config));

    // Wait for the app to load
    await tester.pumpAndSettle();

    // Verify that our app loads without crashing
    expect(find.byType(MaterialApp), findsOneWidget);
  });
}
