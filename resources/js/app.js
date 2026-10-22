import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('pomodoroTimer', () => ({
    selectedMinutes: 25,
    secondsLeft: 25 * 60,
    running: false,
    saving: false,
    canRetrySave: false,
    message: '',
    intervalId: null,

    init() {
        this.secondsLeft = this.selectedMinutes * 60;
    },

    get formattedTime() {
        const minutes = Math.floor(this.secondsLeft / 60).toString().padStart(2, '0');
        const seconds = (this.secondsLeft % 60).toString().padStart(2, '0');
        return `${minutes}:${seconds}`;
    },

    chooseDuration(minutes) {
        if (this.running || this.saving) return;
        this.selectedMinutes = Number(minutes);
        this.secondsLeft = this.selectedMinutes * 60;
        this.message = '';
        this.canRetrySave = false;
    },

    start() {
        if (this.running || this.saving) return;
        if (this.secondsLeft <= 0) this.secondsLeft = this.selectedMinutes * 60;
        this.running = true;
        this.message = '';
        this.intervalId = window.setInterval(() => {
            if (this.secondsLeft > 0) {
                this.secondsLeft -= 1;
            }
            if (this.secondsLeft <= 0) {
                this.pause();
                this.saveCompletedSession();
            }
        }, 1000);
    },

    pause() {
        this.running = false;
        if (this.intervalId !== null) {
            window.clearInterval(this.intervalId);
            this.intervalId = null;
        }
    },

    reset() {
        this.pause();
        this.secondsLeft = this.selectedMinutes * 60;
        this.message = '';
        this.canRetrySave = false;
    },

    localDateString() {
        const now = new Date();
        const pad = (value) => String(value).padStart(2, '0');
        return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;
    },

    async saveCompletedSession() {
        if (this.saving) return;
        this.saving = true;
        this.message = 'Saving completed session…';
        this.canRetrySave = false;

        try {
            const response = await fetch('/study-sessions', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    duration_minutes: this.selectedMinutes,
                    date: this.localDateString(),
                }),
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                const firstError = payload.errors ? Object.values(payload.errors)[0]?.[0] : null;
                throw new Error(firstError || payload.message || 'Could not save the session. Please try again.');
            }

            this.message = `Great work! Your ${this.selectedMinutes}-minute session was saved.`;
            this.secondsLeft = this.selectedMinutes * 60;
            window.dispatchEvent(new CustomEvent('study-session-saved'));
        } catch (error) {
            this.message = error.message || 'Could not save the session. Please retry.';
            this.canRetrySave = true;
        } finally {
            this.saving = false;
        }
    },
}));

Alpine.data('examCountdown', (isoDate) => ({
    isoDate,
    days: '—',
    hours: '—',
    minutes: '—',
    seconds: '—',
    finished: false,
    intervalId: null,

    init() {
        this.update();
        this.intervalId = window.setInterval(() => this.update(), 1000);
    },

    update() {
        const difference = new Date(this.isoDate).getTime() - Date.now();
        if (difference <= 0) {
            this.finished = true;
            this.days = this.hours = this.minutes = this.seconds = '00';
            if (this.intervalId !== null) window.clearInterval(this.intervalId);
            return;
        }

        const totalSeconds = Math.floor(difference / 1000);
        this.days = String(Math.floor(totalSeconds / 86400));
        this.hours = String(Math.floor((totalSeconds % 86400) / 3600)).padStart(2, '0');
        this.minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
        this.seconds = String(totalSeconds % 60).padStart(2, '0');
    },
}));

Alpine.start();
