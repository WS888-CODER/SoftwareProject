

function openImageViewer(imgSrc) {
    let viewer = document.createElement("div");
    viewer.classList.add("image-viewer");
    viewer.innerHTML = `<img src="${imgSrc}"><button onclick="this.parentElement.remove()">Close</button>`;
    document.body.appendChild(viewer);
}


function getPreviousSiblings(elem) {
  let siblings = [];
  while (elem = elem.previousElementSibling) {
    siblings.push(elem);
  }
  return siblings;
}

/*view moo*/

function openModal(id) {
  const modal = document.getElementById('modal');
  const modalImg = document.getElementById('modal-content');
  const modalVideo = document.getElementById('modal-video');
  
  modal.style.display = "block";
  
  if (id.startsWith('photo')) {
    modalImg.src = document.querySelector(`img[onclick="openModal('${id}')"]`).src;
    modalImg.style.display = "block";
    modalVideo.style.display = "none";
  } else if (id.startsWith('video')) {
    modalVideo.src = document.querySelector(`video[onclick="openModal('${id}')"]`).src;
    modalVideo.style.display = "block";
    modalImg.style.display = "none";
  }
}

function closeModal() {
  const modal = document.getElementById('modal');
  modal.style.display = "none";
}