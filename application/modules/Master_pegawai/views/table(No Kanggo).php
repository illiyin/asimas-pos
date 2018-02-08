 <?php foreach ($pegawai->result_array() as $spegawai):?>
              <tr id="tRow<?=$spegawai['id']?>">
                 <td><?=$spegawai['nama'];?></td>
                 <td><?=$spegawai['alamat'];?></td>
                 <td><?=$spegawai['no_telp'];?></td>
                 <td class="hidden-xs"><?=$spegawai['email'];?></td>
                 <td class="hidden-xs"><?=$spegawai['date_add'];?></td>
                 <td><div class="btn-group">
                      <a class="btn btn-default" href="javascript:void(0)" data-toggle="popover" data-placement="left"  data-html="true" title='Hapus Data?' data-content='<button class="btn btn-danger" onclick="delRow(<?=$spegawai['id']?>)" href="#" id="aConfirm">Ya</button>'><i class="fa fa-times"></i></a>
                      <a class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Ubah Data" onclick="showDialogUpdate('<?=$spegawai['id']?>', this)"><i class="fa fa-pencil"></i></a>
                     </div>
                  </td>
              </tr>
           <?php endforeach;?>