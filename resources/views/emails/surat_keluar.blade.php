<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #e0e0e0; padding: 20px; background-color: #f9f9f9;">
    <header style="text-align: center; padding-bottom: 20px; border-bottom: 2px solid #0073e6;">
        <h1 style="color: #0073e6; font-size: 24px;">Surat</h1>
        <p style="margin: 0; color: #555;">Nomor Surat: <strong>{{ $nomor_surat }}</strong></p>
    </header>

    <section style="padding: 20px 0;">
        <h2 style="color: #333; font-size: 20px;">Topik Surat</h2>
        <p style="color: #333; font-size: 16px; margin: 10px 0 20px;"><strong>{{ $topik_surat }}</strong></p>

        <h3 style="color: #333; font-size: 18px; border-bottom: 1px solid #0073e6; padding-bottom: 5px;">Isi Surat</h3>
        <div style="line-height: 1.6; font-size: 16px;">
            @foreach (explode("\n", $isi_surat) as $paragraph)
            @if (trim($paragraph) !== "")
            <p>{{ $paragraph }}</p>
            @endif
            @endforeach
        </div>
    </section>

    <footer style="text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #777; font-size: 14px;">
        <p>Terima kasih telah membaca surat ini.</p>
        <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
    </footer>
</div>