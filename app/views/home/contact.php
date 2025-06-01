<h1><?= htmlspecialchars($page_title ?? 'İletişim') ?></h1>

<div style="max-width: 600px; margin: 0 auto;">
    <p style="text-align: center; color: #666; margin-bottom: 2rem;">
        Bizimle iletişime geçmek için aşağıdaki formu kullanabilirsiniz.
    </p>

    <form method="POST" action="/contact" style="background: #f8f9fa; padding: 2rem; border-radius: 10px; border: 1px solid #e9ecef;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        
        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Adınız *</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="<?= htmlspecialchars($old_data['name'] ?? old('name')) ?>"
                required
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#667eea'"
                onblur="this.style.borderColor='#ced4da'"
            >
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">E-posta Adresiniz *</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?= htmlspecialchars($old_data['email'] ?? old('email')) ?>"
                required
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#667eea'"
                onblur="this.style.borderColor='#ced4da'"
            >
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Mesajınız *</label>
            <textarea 
                id="message" 
                name="message" 
                rows="6" 
                required
                placeholder="Mesajınızı buraya yazın... (En az 10 karakter)"
                style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 6px; font-size: 1rem; box-sizing: border-box; resize: vertical; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#667eea'"
                onblur="this.style.borderColor='#ced4da'"
            ><?= htmlspecialchars($old_data['message'] ?? old('message')) ?></textarea>
        </div>
        
        <button 
            type="submit"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 2rem; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; width: 100%;"
            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
        >
            📤 Mesajı Gönder
        </button>
    </form>
    
    <div style="margin-top: 2rem; text-align: center; color: #666;">
        <p style="margin-bottom: 1rem;">📧 Alternatif olarak doğrudan e-posta gönderebilirsiniz:</p>
        <p style="font-weight: 600; color: #333;">info@simpleframework.com</p>
    </div>
</div>
