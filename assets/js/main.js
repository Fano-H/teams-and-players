import axios from 'axios'

/**
 * For adding players on team form
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

/**
 * Getting data of a team
 */
const getTeamDataUrl = document.getElementsByTagName('body') ? document.getElementsByTagName('body')[0].dataset.getTeamDataUrl : null

async function getTeamData (teamId) {
  let teamdata = 0

  await axios.get(getTeamDataUrl, {
    params: {
      teamId
    }
  }).then(function (response) {
    teamdata = response
  })
  return teamdata
}

/**
 * Setting balance input if there
 * is operation form
 */
function setHTMLBalance () {
  const teamFields = document.querySelectorAll('.custom-operator, .custom-concern')
  const tOperator = document.querySelector('.custom-operator')
  const tConcern = document.querySelector('.custom-concern')

  if (teamFields.length > 0) {
    teamFields.forEach((teamField) => {
      teamField.addEventListener('change', async function (evt) {
        const teamData = await getTeamData(evt.target.value, getTeamDataUrl)
        const balance = teamData.data.moneyBalance
        evt.target.closest('.custom-select-parent').querySelector('.custom-team-balance').value = balance

        if(tConcern.value && tOperator.value && tConcern.value === tOperator.value){
          alert("Same team selection not allowed !")
          // tConcern.value = ""
          // tConcern.dispatchEvent(new Event('change'));
        }

      })
    })
  }
}

/**
 * Fitlering player dropdown list of player
 * according to the concern team selected
 */

function setPlayerDropdown(){
  const playerDropdown = document.querySelector('.custom-concern-player')
  const concernTeam = document.querySelector('.custom-concern')

  if (playerDropdown && concernTeam) {
    playerDropdown.innerHTML = '<option>Select a player</option>'

    concernTeam.addEventListener('change', async function (evt) {
      playerDropdown.innerHTML = '<option>Select a player</option>'

      if(evt.target.value){
        const teamPlayers = await getTeamData(evt.target.value).then((response) => response.data.players)
        
        teamPlayers.forEach((elem) => {
          playerDropdown.innerHTML += '<option value="' + elem.id + '">' + elem.fullname + '</option>'
        })
      }
    })
  }
}



/** 
 * Handling whether purchaser operator team balance is lower than
 * operation amount if the type is a buy operation
 * 
 * And whether purchaser concern team balance is lower than
 * operation amount if the type is a sell operation from operator team
 */

function validating(){
  const operationSelect = document.querySelector('.custom-operation-type-select');

  if(operationSelect){
    const form = operationSelect.closest('form');
    const operatorBalance = form.querySelector('.custom-operator-balance')
    const concernBalance = form.querySelector('.custom-concern-balance')
    const operationAmount = form.querySelector('.custom-operation-amount')

    const changingElems = form.querySelectorAll('.custom-concern-balance,.custom-operator-balance,.custom-operation-amount,.custom-operation-type-select')

    const btnSubmit = form.querySelector('button[type="submit"]')
    btnSubmit.classList.add('disabled')

    changingElems.forEach((elem)=>{
      
      elem.addEventListener('change', function(chngEvt){
     
        if(operatorBalance.value && concernBalance.value){
            if(operationSelect.value === 'buy' && operationAmount.value > operatorBalance.value){
                alert("The operator team has lower balance than the amount to purchase !")
            }
            else if(operationSelect.value == 'sell' && operationAmount.value > concernBalance.value){
                alert('The concern team has lower balance than the sold amount !')
            }
            // else{
            //   btnSubmit.classList.remove('disabled')
            // }
        }

      })
       
    })

  }
}


document.addEventListener('DOMContentLoaded', function(){
  validating()
  setHTMLBalance()
  setPlayerDropdown()
})