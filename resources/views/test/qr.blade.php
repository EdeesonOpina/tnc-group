{{-- QrCode::generate(url('mailto:abc@example.com?subject = Feedback&body = Message')) --}}

{{ QrCode::generate(url('sms:+639234567899')) }}