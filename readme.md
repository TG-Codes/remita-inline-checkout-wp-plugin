---

```markdown
# Remita Inline Checkout WP Plugin

This WordPress plugin integrates Remita Inline Checkout into your website, allowing users to make payments securely using Remita. It features a settings page for easy configuration of your Remita credentials and automatic RRR generation.

---

## 📂 Plugin Structure

```

remita-inline-checkout-wp-plugin/
│
├── remita-inline-checkout-wp-plugin.php
│
├── includes/
│   ├── scripts.php
│   ├── shortcodes.php
│   ├── ajax.php
│   └── admin-settings.php
│
├── templates/
│   └── checkout-form.php
│
└── assets/
└── remita-pay-inline.bundle.js

```

---

## 🚀 Installation

1. Upload the entire `remita-inline-checkout-wp-plugin` folder to the `/wp-content/plugins/` directory of your WordPress site.
2. Activate the plugin via the 'Plugins' menu in WordPress.
3. Navigate to **Settings > Remita Inline Settings** in the WordPress admin to configure your Remita credentials.
4. Use the shortcode `[remita_checkout]` in any page or post where you want the Remita checkout form to appear.

---

## 🔑 Usage

- This plugin dynamically generates RRRs using the Remita Invoice API and displays the Remita Inline Payment widget.
- Use the `[remita_checkout]` shortcode to embed the payment form anywhere on your site.

---

## 🛠️ Configuration

- Navigate to **Settings > Remita Inline Settings** to input:
  - Merchant ID
  - Service Type ID
  - API Key
  - API Token
  - Public Key
- Save your settings to enable payment processing.
- No need to manually edit the code or templates.

---

## ⚠️ Notes

- The plugin automatically generates RRRs and handles payments end-to-end.
- Ensure your Remita credentials are valid and tested in the **Remita demo environment** before going live.
---

## 📞 Support

For issues or questions, please contact your developer or refer to Remita’s official API documentation.

---

## 📝 License

This plugin is provided as-is, without warranty of any kind.

---

**Enjoy! 🚀**
```

---
