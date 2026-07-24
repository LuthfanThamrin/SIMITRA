<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900 shadow-sm rounded-xl border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-medium">Kode Referral Anda</h2>
            <p class="text-gray-500 text-sm mt-1 mb-4">Sebarkan link atau QR code ini kepada calon pelanggan. Pendaftaran yang masuk melalui link/QR ini akan tercatat atas nama Anda.</p>
            
            <div class="text-3xl font-bold text-primary-600 mb-6">
                {{ $this->getKodeReferral() }}
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Referral</label>
                    <div class="flex items-center space-x-3">
                        <input type="text" id="referral-link" readonly value="{{ $this->getLinkReferral() }}" class="w-full bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <x-filament::button onclick="copyToClipboard()">
                            Salin Link
                        </x-filament::button>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">QR Code</label>
                    <div class="inline-block bg-white p-4 rounded-lg border border-gray-200 mb-3">
                        {!! $this->getQrCode() !!}
                    </div>
                    <div>
                        <a href="{{ route('mitra.qr.download') }}"
                           class="fi-btn fi-color-gray fi-size-md fi-btn-color-gray inline-flex items-center gap-x-1.5 rounded-lg px-3 py-2 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                            Unduh QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            var linkValue = document.getElementById("referral-link").value;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(linkValue)
                    .then(function () {
                        showCopyNotification();
                    })
                    .catch(function () {
                        fallbackCopy(linkValue);
                    });
            } else {
                fallbackCopy(linkValue);
            }
        }

        function fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.setAttribute('readonly', '');
            textArea.style.position = 'fixed';
            textArea.style.top = '-9999px';
            textArea.style.left = '-9999px';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            textArea.setSelectionRange(0, textArea.value.length);

            try {
                const copied = document.execCommand('copy');
                if (copied) {
                    showCopyNotification();
                }
            } catch (error) {
                showCopyNotification();
            } finally {
                document.body.removeChild(textArea);
            }
        }

        function showCopyNotification() {
            new FilamentNotification()
                .title('Link berhasil disalin!')
                .success()
                .duration(3000)
                .send();
        }
    </script>
</x-filament-panels::page>
