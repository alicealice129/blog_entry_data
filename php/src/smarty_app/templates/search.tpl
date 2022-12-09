<div>
  <form method="post" action="search.php" class="card p-3 bg-light">
    <legend>検索画面</legend>
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label">日付(UTC)</label>
        <div class="col-sm-3">
          <input type="date" name="date" class="form-control" value={$date}>
        </div>
      </div>
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">URL</label>
      <div class="col-sm-9">
        <input type="text" name="url"  class="form-control" placeholder="URL" value={$url}>
      </div>
    </div>
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">ユーザー名</label>
      <div class="col-sm-9">
        <input type="username" name="username"  class="form-control" placeholder="ユーザー名" value={$username}>
      </div>
    </div>
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">サーバー番号</label>
      <div class="col-sm-9">
        <input type="text" name="server_name" class="form-control" placeholder="サーバー番号" value={$server_name}>
      </div>
    </div>
    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">エントリーNo.</label>
      <div class="col-sm-9">
        <input type="text" name="entry_number" class="form-control" placeholder="エントリーNo.  以上" value={$entry_number}>
      </div>
    </div>
    <input type="submit" name="submit" class="btn btn-primary" value="検索">
  </form>
</div>