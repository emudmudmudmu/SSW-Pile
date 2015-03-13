@layout('template_construction')

@section('title')
          <div class="title"><p>物件登録</p></div>
@endsection
@section('main')
          <form method="post" action="thing_register_check.html" id="regF">
            <div class="thing_reg_query">
              <table>
                <tr>
                  <th><p class="th_p">認定番号</p></th>
                  <td>
                    <span id="constructionNo" class="code"></span>
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th><p class="th_p">進捗状況</p></th>
                  <td>
                    <select name="status" id="status">
@foreach ( $status_list as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th><p class="th_p">識別年度</p></th>
                  <td>
                    <input type="text" name="year" id="year" size="20" maxlength="2" value="{{ date('y') }}" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工会社名</p></th>
                  <td>
                    <select name="constructor" id="constructor">
                      <option value=""></option>
@foreach ( $constructors as $constructor )
                      <option value="{{ pad3($constructor->company_code) }}"{{ ($user->company->company_code == $constructor->company_code) ? ' selected' : '' }}>{{ $constructor->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">施工管理技術者</p></th>
                  <td>
                    <select name="engineer" id="engineer">
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">会社通番</p></th>
                  <td>
                    <span id="company_seq" class="code">1{{ pad3($company_seq) }}</span>
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th><p class="th_p">設計会社</p></th>
                  <td>
                    <select name="design_company" id="design_company">
                      <option value=""></option>
@foreach ( $designers as $designer )
                      <option value="{{ pad3($designer->company_code) }}"{{ ($user->company->company_code == $designer->company_code) ? ' selected' : '' }}>{{ $designer->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">設計担当者</p></th>
                  <td>
                    <select name="architect" id="architect">
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">発注元</p></th>
                  <td>
                    <select name="contractee" id="contractee">
                      <option value=""></option>
@foreach ( $contractee as $company )
                      <option value="{{ pad3($company->company_code) }}">{{ $company->company_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th><p class="th_p">工事名称</p></th>
                  <td>
                    <input type="text" name="construction_name" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">工事場所</p></th>
                  <td>
                    <input type="text" name="work_place" size="40" />
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th><p class="th_p">着工日</p></th>
                  <td>
                    <input type="text" name="sday_yyyy" size="6" />年
                    <input type="text" name="sday_mm" size="6" />月
                    <input type="text" name="sday_dd" size="6" />日
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">完工日</p></th>
                  <td>
                    <input type="text" name="eday_yyyy" size="6" />年
                    <input type="text" name="eday_mm" size="6" />月
                    <input type="text" name="eday_dd" size="6" />日
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">報告書承認日</p></th>
                  <td>
                    <input type="text" name="aday_yyyy" size="6" />年
                    <input type="text" name="aday_mm" size="6" />月
                    <input type="text" name="aday_dd" size="6" />日
                  </td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th></th>
                  <td></td>
                </tr>
                <tr>
                  <th><p class="th_p">打設本数</p></th>
                  <td>
                    <input type="text" name="number" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">材種</p></th>
                  <td>
                    <select name="wood_species" id="material_id">
                      <option value=""></option>
@foreach ( $materials as $material )
                      <option value="{{ $material->material_id }}">{{ $material->material_name }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">種別</p></th>
                  <td>
                    <select name="kind" id="sybt">
                      <option value=""></option>
@foreach ( $sybts as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">種別2</p></th>
                  <td>
                    <select name="kind2" id="sybt2">
                      <option value=""></option>
@foreach ( $sybt2s as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">構造</p></th>
                  <td>
                    <select name="structure" id="kozo">
                      <option value=""></option>
@foreach ( $kozos as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">用途</p></th>
                  <td>
                    <input type="text" name="use" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">基礎形式</p></th>
                  <td>
                    <select name="basis" id="kiso">
                      <option value=""></option>
@foreach ( $kisos as $k => $v )
                      <option value="{{ $v }}">{{ $k }}</option>
@endforeach
                    </select>
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">階数</p></th>
                  <td>
                    <input type="text" name="floor" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">高さ(m)</p></th>
                  <td>
                    <input type="text" name="height" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">軒高(m)</p></th>
                  <td>
                    <input type="text" name="hotels_high" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">延べ面積(㎡)</p></th>
                  <td>
                    <input type="text" name="area" size="40" />
                  </td>
                </tr>
                <tr>
                  <th><p class="th_p">最大施工深さ(m)</p></th>
                  <td>
                    <input type="text" name="depth_construction" size="40" />
                  </td>
                </tr>
              </table>

              <input class="thing_register_button" id="thing_register_button" type="button" name="submit" />
            </div>
          </form>

@endsection