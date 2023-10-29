/**
 * To add players on team form
 * (new)
 */
document
  .querySelectorAll('.custom-add-player-button')
  .forEach(btn => {
    btn.addEventListener('click', addPlayerToCollection)
  })

function addPlayerToCollection (e) {
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass)
  console.log(collectionHolder)
  const item = document.createElement('div')

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    )

  collectionHolder.appendChild(item)

  collectionHolder.dataset.index++

  addPlayerFormDeleteLink(item)
};

/**
 * For removing players from team form
 * While adding new team
 */
function addPlayerFormDeleteLink (item) {
  const removeFormButton = document.createElement('button')
  removeFormButton.classList.add('btn', 'btn-sm', 'btn-danger', 'ms-auto', 'd-block')
  removeFormButton.innerText = 'Delete this player'

  item.append(removeFormButton)

  removeFormButton.addEventListener('click', (e) => {
    e.preventDefault()
    item.remove()
  })
}

function removingExistingPlayer () {
  const removeBtns = document.querySelectorAll('.custom-delete-existing-player')

  if (removeBtns.length > 0) {
    removeBtns.forEach((rmvBtn) => {
      rmvBtn.addEventListener('click', function (evt) {
        const item = rmvBtn.closest('.custom-existing-player')
        item.remove()
      })
    })
  }
}

removingExistingPlayer()
