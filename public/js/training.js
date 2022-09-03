'use strict';

{
  let i = 0;

  const parent = document.getElementById('addparent');

  document.getElementById('addsets').addEventListener('click', () => {
    i++;

    const addweightli = document.createElement('li');
    addweightli.textContent = '重量' + '(' + (i + 1) + 'セット目)';

    const addweight = document.createElement('input');
    addweight.setAttribute('type', 'number');
    addweight.setAttribute('step', '0.25');
    addweight.setAttribute('name', 'weight' + i);
    addweight.setAttribute('id', 'weight');
    addweight.setAttribute('class', 'added' + i);

    const addrepsli = document.createElement('li');
    addrepsli.textContent = 'レップ数' + '(' + (i + 1) + 'セット目)';

    const addreps = document.createElement('input');
    addreps.setAttribute('type', 'number');
    addreps.setAttribute('name', 'reps' + i);
    addreps.setAttribute('id', 'reps');
    addreps.setAttribute('class', 'added' + i);

    console.log(i);

    parent.appendChild(addweightli);
    parent.appendChild(addweight);
    parent.appendChild(addrepsli);
    parent.appendChild(addreps);
  });

  // document.getElementById('deletesets').addEventListener('click', () => {
  //   const item1 = document.querySelectorAll('li ')[1];

  //   item1.remove();
  // });
  
  // なぜか動かない！フォームの削除ができない
  document.getElementById('deletesets').addEventListener('click', () => {
    if (i > 0) {

      const added = document.querySelectorAll('.added')[1];
      
      // added.remove();
      document.querySelector('.addparent').removeChild(added);

      i--;
      console.log(i);
    } else {
      return;
  }});
}