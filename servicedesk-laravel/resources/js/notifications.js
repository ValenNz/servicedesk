document.addEventListener('alpine:init', () => {
    Alpine.store('notifications', {
        items: [],
        add(type, message, title = '', duration = 4000) {
            const id = Date.now() + Math.random();
            this.items.push({ id, type, message, title, duration });
            
            if (duration > 0) {
                setTimeout(() => this.remove(id), duration);
            }
        },
        remove(id) {
            this.items = this.items.filter(i => i.id !== id);
        }
    });

    window.notify = {
        success: (message, title = 'Success!') => Alpine.store('notifications').add('success', message, title),
        error: (message, title = 'Error') => Alpine.store('notifications').add('error', message, title),
        warning: (message, title = 'Warning') => Alpine.store('notifications').add('warning', message, title),
        info: (message, title = 'Information') => Alpine.store('notifications').add('info', message, title),
    };
});