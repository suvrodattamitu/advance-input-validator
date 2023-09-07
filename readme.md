### How to use it in another class?

**here's an example**

```php
    *use DemoProject\App\Services\InputValidator;

    try {
         $data = [
            'name' => 'suvro',
            'rows' => '10',
            'random_data' => [
                ['email' => 'email2@email.com'],
                ['email' => '<script>email3@email.com</script>'],
            ]
        ];

        (new InputValidator())->validate($data);

        update_option('demo_project_settings', maybe_serialize($data), "no");

        wp_send_json_success([
            'message' => __("Settings updated successfully!", "demoproject")
        ], 200);
    } catch (\Exception $exception) {
        wp_send_json_error([
            'errors' => json_decode($exception->getMessage(), true)
        ], 423);
    }*
```