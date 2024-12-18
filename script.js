const socialMedia = document.getElementById('socialMedia');
const work = document.getElementById('work');
const videos = document.getElementById('videos');
const games = document.getElementById('games');
const devices = document.getElementById('devices');
const deviceCount = document.getElementById('deviceCount');
const speedDisplay = document.querySelector('.speed-display');
const needle = document.querySelector('.meter-needle');

// Função para calcular velocidade
function calculateSpeed() {
  let speed = 200; // Velocidade base

  if (socialMedia.checked) speed += 20;
  if (work.checked) speed += 50;
  if (videos.checked) speed += 100;
  if (games.checked) speed += 150;

  // Aumenta com a quantidade de dispositivos
  speed += devices.value * 10;

  // Ajusta visualização
  if (speed > 1000) speed = 1000; // Limite de 1000 MEGA

  speedDisplay.textContent = `${speed}MEGA`;
  updateNeedle(speed);
}

// Atualiza a agulha do velocímetro
function updateNeedle(speed) {
  const angle = (speed / 1000) * 180 - 45;
  needle.style.transform = `translate(-50%, -100%) rotate(${angle}deg)`;
}

// Atualiza contagem de dispositivos
devices.addEventListener('input', () => {
  deviceCount.textContent = devices.value;
  calculateSpeed();
});

// Adiciona eventos aos switches
[socialMedia, work, videos, games].forEach((input) => {
  input.addEventListener('change', calculateSpeed);
});

// Inicializa a velocidade
calculateSpeed();
